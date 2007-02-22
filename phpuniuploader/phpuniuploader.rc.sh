#! /bin/sh
### BEGIN INIT INFO
# Provides:          single
# Required-Start:    $local_fs killprocs
# Required-Stop:
# Default-Start:     1
# Default-Stop:
### END INIT INFO
# 	--running           - Check if a named daemon is running
#	--restart           - Restart a named daemon client
#	--stop 

# This is an example startup script based on http://libslack.org/daemon/
# Add the script to /etc/init.d on a linux box and make links from the appropiate runlevel directories.
# Also modify the SCRIPT variable to point to the phpUniUploader.php file
# Start it up as the root user or reboot your system ... /etc/init.d/phpuniuploader start

PATH=/sbin:/bin
PHP=/usr/bin/php
DAEMON=/usr/bin/daemon
SCRIPT=/path/to/phpUniUploader/phpUniUploader.php

. /lib/lsb/init-functions

do_start () {
	$DAEMON --name="phpuniuploader" $PHP $SCRIPT 
}

case "$1" in
  start)
	$DAEMON --name="phpuniuploader" $PHP $SCRIPT	
	;;
  restart|reload|force-reload)
	$DAEMON --name="phpuniuploader" --stop
	$DAEMON --name="phpuniuploader" $PHP $SCRIPT 
	;;
  status)
	$DAEMON -v --name="phpuniuploader" --running
	;;
  stop)
	$DAEMON --name="phpuniuploader" --stop
	;;
  *)
	echo "Usage: $0 start|restart|status|stop" >&2
	exit 3
	;;
esac
