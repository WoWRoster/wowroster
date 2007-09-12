.PHONY:all, package

all:

package:
	svn export trunk roster
	rm roster/version_match.php
	sed -i "/define('ROSTER_VERSION'/c\
define('ROSTER_VERSION','1.9.9.`svnversion trunk`');" roster/lib/constants.php
	zip -r roster.zip roster
	tar -czf roster.tar.gz roster
	rm -rf roster
