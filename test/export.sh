#!/bin/bash

./symfony propel:insert-sql --env=test --no-confirmation
./symfony propel:data-load --env=test test/fixtures/common

mysql -utest -pr4BBPRyt628YDF -D collectorsquest_test < data/sql/lib.model.views.sql
mysql -utest -pr4BBPRyt628YDF -D collectorsquest_test < data/sql/lib.model.procedures.sql
mysql -utest -pr4BBPRyt628YDF -D collectorsquest_test < data/sql/lib.model.triggers.sql

for table in `mysql -utest -pr4BBPRyt628YDF collectorsquest_test -e 'show tables' | egrep -v 'Tables_in_' `; do
  mysqldump -utest -pr4BBPRyt628YDF --insert-ignore --skip-set-charset -c -C --compact --opt -Q collectorsquest_test $table > test/schemas/$table.sql
done
