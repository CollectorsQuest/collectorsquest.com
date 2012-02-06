package "rubygems"
package "ruby-devel"
package "libxml2-devel"
package "libxslt-devel"
package "curl-devel"
package "openssl-devel"

bash "Installing/Updating gems" do
  code <<-EOH
    /usr/bin/gem install --no-rdoc --no-ri --version=1.3.6 rubygems-update
    /usr/bin/update_rubygems --version=1.3.6

    /usr/bin/gem install --no-rdoc --no-ri bundle
    /usr/bin/gem install --no-rdoc --no-ri passenger
    /usr/bin/gem install --no-rdoc --no-ri rake
  EOH
  not_if "test -e /usr/lib/ruby/gems/1.8/gems/passenger-3.0.11/bin/passenger"
end

directory "/tmp/bundler" do
  owner "root"
  group "root"
  mode 0755
end
template "/tmp/bundler/Gemfile" do
  source "Gemfile"
  mode "0644"
end
template "/tmp/bundler/Gemfile.lock" do
  source "Gemfile.lock"
  mode "0644"
end
execute "Install deployinator gems" do
  cwd "/tmp/bundler/"
  command "/usr/bin/bundle install"
  not_if "test -e "
end

execute "Install Passenger Apache2 module" do
  command "/usr/bin/gem install --no-rdoc --no-ri passenger && /usr/bin/passenger-install-apache2-module -a"
  not_if "test -e /usr/lib/ruby/gems/1.8/gems/passenger-3.0.11/ext/apache2/mod_passenger.so"
end

template "/etc/httpd/conf.d/passenger.conf" do
  source "passenger.conf"
  mode "0644"
end

service "apache" do
  action :restart
end
