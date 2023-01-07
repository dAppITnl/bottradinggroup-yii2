#!/bin/bash

#Set the variable which equal to zero
min_count=0
LOGFILE="/var/www/vhosts/bottradinggroup.nl/yii2_code/console/runtime/logs/runconsolescheduleatmidnight.log"
DATE=`date --date='today' '+%Y-%m-%d %H'`

/usr/share/php/bottradinggroup-yii2/yii schedular/updatemaxsignals

/usr/share/php/bottradinggroup-yii2/yii schedular/updatediscordrole

# Send a mail to given email id when errors found in log
SUBJECT="[BTG-API-V1 log]: $count Error/Warning/Critical found for $DATE hour"
MESSAGE="/tmp/BTGWarningEmailMsg.txt"
TO="webadmin@bottradinggroup.nl,emiel@bottradinggroup.nl"
#BCC="nwkcoins@kes.nl"

rm $MESSAGE

echo "ATTENTION: $count error/warning/critical(s) are found in BTG API-v1 app.log for $DATE hour." >> $MESSAGE
echo "Time: `date --date='today' '+%Y-%m-%d %X'`." >> $MESSAGE
echo "Error messages in the log file as below, please Check with Linux admin." >> $MESSAGE
echo "+------------------------------------------------------------------------------------+" >> $MESSAGE
echo "$lines" >> $MESSAGE
echo "+------------------------------------------------------------------------------------+" >> $MESSAGE

mail -s "$SUBJECT" "$TO" -r noreply@bottradinggroup.nl < $MESSAGE

#echo "TO: $TO"
#echo "SUBJ: $SUBJECT"
#cat $MESSAGE

fi
