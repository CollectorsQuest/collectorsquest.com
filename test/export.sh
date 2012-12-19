#!/bin/bash

echo "DROP DATABASE IF EXISTS collectorsquest_test;" | mysql -u root
echo "CREATE DATABASE collectorsquest_test DEFAULT CHARACTER SET utf8;" | mysql -u root

php symfony propel:build-sql && php symfony propel:insert-sql --env=test --no-confirmation
php -d memory_limit=512M ./symfony propel:data-load --env=test --connection=propel test/fixtures/common/propel
php -d memory_limit=512M ./symfony propel:data-load --env=test --connection=archive test/fixtures/common/archive
php -d memory_limit=512M ./symfony propel:data-load --env=test --connection=blog test/fixtures/common/blog

mysql -uroot -D collectorsquest_test < data/sql/lib.model.views.sql
mysql -uroot -D collectorsquest_test < data/sql/lib.model.procedures.sql
mysql -uroot -D collectorsquest_test < data/sql/lib.model.triggers.sql

for table in `mysql -uroot collectorsquest_test -e 'show tables' | egrep -v 'Tables_in_' `; do
  mysqldump -uroot --insert-ignore --skip-set-charset -c -C --compact --opt -Q collectorsquest_test $table > test/schemas/$table.sql
done
