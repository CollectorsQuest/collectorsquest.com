include_recipe "zend"

template "/usr/local/zend/etc/conf.d/browscap.ini" do
  source "browscap.ini"
  mode "0664"
end
remote_file "/usr/local/zend/etc/conf.d/lite_php_browscap.txt" do
  source "http://browsers.garykeith.com/stream.asp?Lite_PHP_BrowsCapINI"
  mode "0664"
end
