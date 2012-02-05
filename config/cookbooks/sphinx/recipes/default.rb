#
# Cookbook Name:: sphinx
# Recipe:: default
#

remote_file "/tmp/sphinx-2.0.3-1.rhel6.i386.rpm" do
  source "http://sphinxsearch.com/files/sphinx-2.0.3-1.rhel6.i386.rpm"
  not_if { ::File.exists?("/tmp/sphinx-2.0.3-1.rhel6.i386.rpm") }
end

execute "Install Sphinx" do
  cwd "/tmp"
  command "rpm -iUv /tmp/sphinx-2.0.3-1.rhel6.i386.rpm"
  not_if { ::File.exists?("/usr/bin/searchd") && system("/usr/bin/searchd -h | grep 'Sphinx 2.0.3-id64-release'") }
end

directory "/var/lib/sphinx" do
  owner "sphinx"
  group "sphinx"
  mode "0755"
  action :create
end

directory "/var/lib/sphinx/website" do
  owner "sphinx"
  group "sphinx"
  mode "0755"
  action :create
end

execute "Install Sphinx init.d/ Script" do
  cwd "/"
  command "rm /etc/init.d/searchd && ln -s /www/init.d/searchd /etc/init.d/searchd"
  not_if "test -L /etc/init.d/searchd"
end
