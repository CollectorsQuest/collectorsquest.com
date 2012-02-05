package "phpmyadmin-zend-server-php-5.3"
template "/usr/local/zend/gui/lighttpd/etc/lighttpd.conf" do
  notifies :restart, "service[zend]"
end

template "/etc/phpMyAdmin/config.inc.php" do
  mode "0644"
end
template "/usr/local/zend/etc/conf.d/optimizerplus.ini" do
  mode "0664"
end
template "/usr/local/zend/etc/conf.d/development.ini" do
  mode "0664"
end

link "/var/log/apache2" do
  to "/var/log/httpd"
  not_if "test -L /var/log/apache2"
end
