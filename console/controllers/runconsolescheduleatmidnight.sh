#!/bin/bash

cd /usr/share/php/bottradinggroup-yii2
echo "updatemaxsignals... "
`./yii schedular/updatemaxsignals`

echo "updatediscordrole... "
`./yii schedular/updatediscordrole`

echo "ready!"
