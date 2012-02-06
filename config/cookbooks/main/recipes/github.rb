
bash "Install the RSA identity" do
  code <<-EOH
    cp /vagrant/id_rsa ~/.ssh/id_rsa
    exec /usr/bin/ssh-agent zsh
    ssh-add ~/.ssh/id_rsa
  EOH
end

bash "Checkout /www from GitHub" do
  code <<-EOH
    git clone git@github.com:CollectorsQuest/server.git /www
    cd /www && git submodule update --init --recursive
  EOH
  not_if "test -d /www"
end

bash "Checkout /www from GitHub" do
  code <<-EOH
    cd /www
    git pull origin master
    git submodule update --recursive
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
