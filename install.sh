#! /bin/bash
# WoWRoster install shell script. Tested on Linux, probably works on Mac OSX. Won't work on Windows.
# $Id$

echo 'At any point in the script, press Control-C to abort'

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

# Test for index.php as a sign we're in an existing roster install.
if [ ! -f index.php ]
then
	if [ "`ls`" != 'install.sh' ]
	then
		echo 'Please put this script in an empty directory in which to install WoWRoster'
		exit 1
	fi

	wget=
	svn=
	if [ -n "`which wget`" -a -n "`which tar`" ]
	then
		wget=yes
		echo 'Installation using wget is possible'
	else
		echo 'Installation using wget is NOT possible because you do not have wget or tar installed'
	fi

	if [ -n "`which svn`" ]
	then
		svn=yes
		echo 'Installation from SVN is possible'
	else
		echo 'Installation from SVN is NOT possible because you do not have subversion client installed'
	fi

	while :
	do
		read -p "Enter 'svn' to get latest SVN. Enter 'wget' to get last release: " mode
		if [ "$mode" == 'svn' -a -n "$svn" -o "$mode" == 'wget' -a -n "$wget" ]
		then
			break;
		fi
		echo 'Invalid input'
	done

	if [ $mode == 'svn' ]
	then
		svn checkout 'http://wowroster.googlecode.com/svn/trunk'
		rm -f 'trunk/install.sh'
		mv trunk/* trunk/.htaccess trunk/.svn .
		rm -rf trunk
	elif [ $mode == 'wget' ]
	then
		wget -O - 'www.wowroster.net/uploads/roster_latest.tar.gz' | tar -xz
		mv roster/* roster/.htaccess .
		rm -rf roster
	fi
fi

# Test for index.php again, to see if download failed
if [ ! -f index.php ]
then
	echo "Download failed: index.php does not exist"
	exit 1
fi

# Test for existing conf.php
if [ -f conf.php -a ! -w conf.php ]
then
	echo 'conf.php exists and is not writable. This suggests there is an existing'
	echo 'installation here. Delete the file (rm conf.php) and rerun the script'
	exit 1
fi

# Test for php version, but only if a php binary is available.
if [ -z "`which php`" ]
then
	echo "PHP version could not be checked: Command line php not available"
elif php -r 'exit((int)!version_compare(phpversion(),"5.1.0","<"));'
then
	echo "php version is too low: `php -r 'echo phpversion()'` php version 5.1.0 or higher is required."
fi

# Get the mysql connect data
echo 'Attempting to connect to the database with default data.'
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
		if [ `echo 'select substring_index(version(),".",2) >= 4.1;' | $MYSQL --skip-column-names` -eq 1 ]
		then
			# Create the database if needed, and break the while loop
			echo "CREATE DATABASE IF NOT EXISTS \`${db_name}\`" | $MYSQL
			MYSQL="${MYSQL} -D${db_name}"
			break
		else
			mysql_version=`echo 'select version();' | $MYSQL --skip-column-names`
			echo "Your MySQL version is too low. Your mysql version is ${mysql_version}. MySQL v4.1.0"
			echo "or higher is required."
			exit 1
		fi
	fi

	echo '--------------------'

	echo 'Only MySQL databases are supported at this time.'
	read -p "Database host [${db_host}]? " answer
	if [ $answer ]
	then
		db_host=$answer
	fi
	read -p "Database user [${db_user}]? " answer
	if [ $answer ]
	then
		db_user=$answer
	fi
	read -s -p "Database password? " answer
	echo ''
	if [ $answer ]
	then
		db_pass=$answer
	fi
	read -p "Database name [${db_name}]? " answer
	if [ $answer ]
	then
		db_name=$answer
	fi
	read -p "Table prefix [${db_prefix}]? " answer
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

# Update the version number
version=`cat version.txt | cut -f2 -d\> | cut -f1 -d\<`

$MYSQL << !
UPDATE \`${db_prefix}config\` SET \`config_value\` = '${version}' WHERE \`config_name\` = 'version';
!

echo '--------------------'
echo 'The database has been imported.'

# Locale setting
while :
do
	read -p "Please enter your prefered language code (enUS, deDE, frFR, esES): " lang
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
while :
do
	read -s -p "Please enter your desired password: " pass1
	echo
	read -s -p "Please confirm your password: " pass2
	echo
	if [ ${pass1} == ${pass2} ]
	then
		break
	fi
	echo 'Your passwords do not match. Please try again'
done

$MYSQL << !
INSERT INTO \`${db_prefix}account\` (\`account_id\`,\`name\`,\`hash\`) VALUES
(1,'Guild',md5('${pass1}')),
(2,'Officer',md5('${pass1}')),
(3,'Admin',md5('${pass1}'));
!

# Delete web install files, otherwise roster will complain
if [ ! -f version_match.php ]
then
	rm -f install.php upgrade.php
fi

echo 'WoWRoster installation is successful.'
