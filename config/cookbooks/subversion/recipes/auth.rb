directory "/root/.subversion/auth/svn.simple" do
  mode 0700
  owner "root"
  group "root"
  action :create
  recursive true
end

execute "Copy the subversion auth file to the right place" do
  command "cp /vagrant/subversion /root/.subversion/auth/svn.simple/1af3aeb1c737077f8c1c445912e191be"
  not_if "test -e /root/.subversion/auth/svn.simple/1af3aeb1c737077f8c1c445912e191be"
end
