include_recipe "zend"

execute "Install Translit PHP module" do
  command "/usr/local/zend/bin/pecl install channel://pecl.php.net/translit-0.6.1"
  not_if "test -e /usr/local/zend/lib/php_extensions/translit.so"
end

template "/usr/local/zend/etc/conf.d/translit.ini" do
  source "translit.ini"
  mode "0664"
end
