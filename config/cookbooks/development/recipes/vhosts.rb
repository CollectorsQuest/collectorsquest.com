link "/etc/httpd/conf.d/collectorsquest.dev.conf" do
  to "/www/etc/apache2/sites-available/collectorsquest.dev"
  not_if "test -L /etc/httpd/conf.d/collectorsquest.dev.conf"
  notifies :restart, "service[apache]"
end

link "/etc/httpd/conf.d/cqcdns.dev.conf" do
  to "/www/etc/apache2/sites-available/cqcdns.dev"
  not_if "test -L /etc/httpd/conf.d/cqcdns.dev.conf"
  notifies :restart, "service[apache]"
end

link "/www/vhosts/collectorsquest.dev" do
  to "/www/vhosts/collectorsquest.com"
  not_if "test -L /www/vhosts/collectorsquest.dev"
end

link "/www/vhosts/collectorsquest.com/releases/trunk" do
  to "/mnt/vhosts/collectorsquest.com"
  not_if "test -L /www/vhosts/collectorsquest.com/releases/trunk"
end

link "/www/vhosts/collectorsquest.com/current" do
  to "/www/vhosts/collectorsquest.com/releases/trunk"
  not_if "test -L /www/vhosts/collectorsquest.com/current"
end

link "/www/vhosts/collectorsquest.com/current/config/databases.yml" do
  to "/www/vhosts/collectorsquest.com/current/config/databases.dist.yml"
  not_if "test -e /www/vhosts/collectorsquest.com/current/config/databases.yml"
end

link "/www/vhosts/cqcdns.dev" do
  to "/mnt/vhosts/cqcdns.com"
  not_if "test -L /www/vhosts/cqcdns.dev"
end
