include_recipe "zend"

remote_file "/tmp/xhprof.tar.gz" do
  source "https://nodeload.github.com/preinheimer/xhprof/tarball/01b6c75"
  not_if { ::File.exists?("/tmp/xhprof.tar.gz") }
end

bash "Installing xhprof.so" do
  cwd "/tmp"
  code <<-EOH
    tar xfz /tmp/xhprof.tar.gz && cd /tmp/preinheimer-xhprof-01b6c75/extension
    /usr/local/zend/bin/phpize && ./configure --with-php-config=/usr/local/zend/bin/php-config
    make && make install
  EOH
  not_if { ::File.exists?("/usr/local/zend/lib/php_extensions/xhprof.so") }
end

template "/usr/local/zend/etc/conf.d/xhprof.ini" do
  source "xhprof.ini"
  mode "0664"
end
