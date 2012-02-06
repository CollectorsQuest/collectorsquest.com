require_recipe "zend"

remote_file "/tmp/lucid-scached_0.90.6_i386.deb" do
  source "http://scache.nanona.fi/dl/lucid-scached_0.90.6_i386.deb"
  not_if { ::File.exists?("/tmp/lucid-scached_0.90.6_amd64.deb") }
end

remote_file "/tmp/scache-0.90.6.tar.bz2" do
  source "http://scache.nanona.fi/dl/scache-0.90.6.tar.bz2"
  not_if { ::File.exists?("/tmp/scache-0.90.6.tar.bz2") }
end

bash "Installing scached and php5-scache" do
  cwd "/tmp"
  code <<-EOH
    dpkg -i /tmp/lucid-scached_0.90.6_i386.deb
    tar xfj scache-0.90.6.tar.bz2 && cd /tmp/scache-0.90.6/ext
    /usr/local/zend/bin/phpize && ./configure --with-php-config=/usr/local/zend/bin/php-config
    make && make install
  EOH
end
