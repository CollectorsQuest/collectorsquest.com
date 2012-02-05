# development cookbook

require_recipe "memcached"
require_recipe "development::zend"
require_recipe "development::vhosts"
require_recipe "development::gearman"
require_recipe "development::vim"

service "apache" do
  action :restart
end

template "/etc/hosts" do
  mode "0644"
end

package "graphviz"
package "rubygems"

bash "Install Capifony" do
  code <<-EOH
    /usr/bin/gem install --no-rdoc --no-ri capistrano
    /usr/bin/gem install --no-rdoc --no-ri capifony
  EOH
end

service "zend" do
  action :restart
end
