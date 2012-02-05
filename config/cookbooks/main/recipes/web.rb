# web cookbook

require_recipe "zend"
require_recipe "zend::xsendfile"
require_recipe "zend::browscap"
require_recipe "zend::passenger"
require_recipe "zend::modperl"
# require_recipe "zend::gearman"
require_recipe "zend::xhprof"

# require_recipe "zend::functional"
# require_recipe "zend::scached"

bash "Install Symfony 1.4.x from PEAR" do
  code <<-EOH
    /usr/local/zend/bin/pear update-channels
    /usr/local/zend/bin/pear channel-discover pear.symfony-project.com
    /usr/local/zend/bin/pear upgrade symfony/symfony
  EOH
end

service "zend" do
  action :restart
end
