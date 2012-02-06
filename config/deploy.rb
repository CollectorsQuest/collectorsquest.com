set :application, "collectorsquest"
set :domain,      "#{application}.com"
set :deploy_to,   "/www/vhosts/#{domain}"

set :repository,  "file:///home/svn/collectorsquest/vhosts/#{domain}"
set :scm,         :subversion
set :user,        "ubuntu"
set :use_sudo,    true
set :group_writable, false

set :symfony_lib, "/www/libs/symfony-1.4.x"
set :php_bin,     "/usr/local/zend/bin/php"

role :web,        "collectorsquest.com"
role :app,        "collectorsquest.com"
role :db,         "collectorsquest.com", :primary => true

set  :keep_releases,  3
