#! /bin/bash
# Roster install shellscript. Tested on linux, probably works on mac OSX. Won't work on windows.

# Test for required tools
for tool in mysql
do
	if [ -z `which $tool` ]
	then
		echo "Required command-line tool $tool is not on the search path"
		exit 1
	fi
done

# Test for write permission in current directory
if [ ! -w . ]
then
	echo 'During the installation some files are created. Please ensure you have write'
	echo 'permission in the current directory.'
	exit 1
fi

if [ -f conf.php -a ! -w conf.php ]
then
	echo 'conf.php exists and is not writable. This suggests there is an existing'
	echo 'installation here. Delete the file (rm conf.php) and rerun the script'
	exit 1
fi

echo 'At any point in the script, press Control-C to abort'

# Get the mysql connect data
echo 'Attemptying to connect to the database with default data.'
db_host=localhost
db_user=root
db_pass=
db_name=roster
db_prefix=roster_
db_type=mysql
while : 
do
	echo '--------------------'
	MYSQL="$db_type -h${db_host} -u${db_user} ${db_pass:+-p${db_pass}}"

	# Try to connect. Insert an empty query to avoid interactive mode
	if echo '' | $MYSQL
	then
		# Create the database if needed, and break the while loop
		echo "CREATE DATABASE IF NOT EXISTS \`${db_name}\`" | $MYSQL
		MYSQL="${MYSQL} -D${db_name}"
		break
	fi

	echo '--------------------'

	echo 'Only mysql databases are supported at this time.'
	read -p "Database host [${db_host}] ?" answer
	if [ $answer ]
	then
		db_host=$answer
	fi
	read -p "Database user [${db_user}] ?" answer
	if [ $answer ]
	then
		db_user=$answer
	fi
	read -s -p "Database password ?" answer
	echo ''
	if [ $answer ]
	then
		db_pass=$answer
	fi
	read -p "Database name [${db_name}] ?" answer
	if [ $answer ]
	then
		db_name=$answer
	fi
	read -p "Table prefix [${db_prefix}] ?" answer
	if [ $answer ]
	then
		db_prefix=$answer
	fi
done

# We've got valid connect data, write the config file.
cat > conf.php << !
<?php
/**
 * AUTO-GENERATED CONF FILE
 * DO NOT EDIT !!!
 */

\$db_config['host']         = '${db_host}';
\$db_config['username']     = '${db_user}';
\$db_config['password']     = '${db_pass}';
\$db_config['database']     = '${db_name}';
\$db_config['table_prefix'] = '${db_prefix}';
\$db_config['dbtype']       = '${db_type}';

define('ROSTER_INSTALLED', true);
!

chmod -wx conf.php
if [ -e cache -a ! -d cache ]
then
	rm -f cache
	mkdir cache
elif [ ! -e cache ]
then
	mkdir cache
fi
chmod +rwx cache

echo '--------------------'
echo 'The config file and cache directory have been created.'
echo '--------------------'

# Pipe the SQL script into the mysql command line tool, skipping comments and replacing the prefix where needed.
sed "s/renprefix_/${db_prefix}/;/^#/d;/^\$/d" lib/dbal/structure/mysql_structure.sql lib/dbal/structure/mysql_data.sql | $MYSQL

echo '--------------------'
echo 'The database has been imported.'

# Locale setting
while :
do
	read -p "Please enter your prefered language code: " lang
	if [ ! $lang ]
	then
		continue
	fi
	if [ $lang == 'enUS' -o $lang == 'deDE' -o $lang == 'frFR' -o $lang == 'esES' ]
	then
		break
	fi
	echo 'Invalid language code'
done

$MYSQL << !
UPDATE \`${db_prefix}config\` SET \`config_value\` = '${lang}' WHERE \`config_name\` = 'locale';
!

# Password setting
if [ ! `which md5sum` ]
then
	echo "The md5sum program is not available. Your password has been set to 'admin'. Please change this via the web interface as soon as possible."
	# md5 hash for 'admin'
	pass='456b7016a916a4b178dd72b947c152b7'
else
	while :
	do
		read -s -p "Please enter your desired password: " pass1
		echo
		read -s -p "Please confirm your password: " pass2
		echo
		if [ ${pass1} == ${pass2} ]
		then
			pass=`echo $pass1 | md5sum`
			pass=${pass:0:32}
			break
		fi
		echo 'Your passwords did not match. Please retry'
	done
fi

$MYSQL << !
INSERT INTO \`${db_prefix}account\` (\`account_id\`,\`name\`,\`hash\`) VALUES
(1,'Guild','${pass}'),
(2,'Officer','${pass}'),
(3,'Admin','${pass}');
!

# Delete web install files, otherwise roster will complain
if [ ! -f version_match.php ]
then
	rm -f install.php upgrade.php
fi

echo 'Roster installation is successful.'
