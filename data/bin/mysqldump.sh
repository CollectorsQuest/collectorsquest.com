#!/bin/bash
db=$1
if [ "$db" = "" ]; then
echo "Usage: $0 db_name"
exit 1
fi
mkdir $$
cd $$
clear
for table in `mysql $db -e 'show tables' | egrep -v 'Tables_in_' `; do
echo "Dumping $table"
mysqldump --opt -Q $db $table > $table.sql
done
if [ "$table" = "" ]; then
echo "No tables found in db: $db"
fi