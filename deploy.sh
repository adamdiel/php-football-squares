#!/bin/bash
echo "Deploying da squares"
git pull origin master
cp * -r /var/www/html
echo "Done deploying ... wow that was really fast!"