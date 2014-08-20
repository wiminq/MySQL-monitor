#!/bin/bash

basedir="/usr/local/mysql-monitor"

arg=$1

case "$arg" in
	'start' )
		cd $basedir && nohup ./mysql-monitor.py & > /dev/null 2>&1
#		echo "ok"
		;;
	'stop' )
		killall python & > /dev/null 2>&1
		;;

esac

exit 0
