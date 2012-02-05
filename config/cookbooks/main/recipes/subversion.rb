require_recipe "subversion::client"
require_recipe "subversion::auth"

execute "Checkout /www from subversion" do
  command "svn co -q --non-interactive --trust-server-cert svn://184.73.239.190/server /www"
  not_if "test -d /www"
end

bash "Update /www from subversion" do
  code <<-EOH
    svn cleanup /www
    svn up -q --ignore-externals --non-interactive --trust-server-cert /www
  EOH
end

bash "Update /www permissions" do
  code <<-EOH
    chmod -R 777 /www/tmp
    chown -R apache:apache /www/tmp
    chown -R apache:apache /www/logs
    chown -R sphinx:sphinx /www/logs/sphinx
    chown -R apache:apache /www/vhosts/collectorsquest.com/shared
  EOH
end
