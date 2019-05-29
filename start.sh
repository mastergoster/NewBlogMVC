#!/bin/bash

docker-compose build

docker-compose -f docker-compose.yml up -d

echo
echo "#-----------------------------------------------------------"
echo "#"
echo "# Please check your browser to see if it is running, use your"
echo "#"
echo "#-----------------------------------------------------------"
echo

exit 0
