include_recipe "zend"
package "httpd-devel"

remote_file "/tmp/mod_xsendfile-0.12.tar.gz" do
  source "https://tn123.org/mod_xsendfile/mod_xsendfile-0.12.tar.gz"
  not_if { ::File.exists?("/tmp/mod_xsendfile-0.12.tar.gz") }
end

execute "Extract mod_xsendfile source" do
  cwd "/tmp"
  command "tar -zxf /tmp/mod_xsendfile-0.12.tar.gz"
  not_if { ::File.exists?("/tmp/mod_xsendfile-0.12") }
end

bash "Build and Install mod_xsendfile" do
  cwd "/tmp/mod_xsendfile-0.12"
  code "apxs2 -cia mod_xsendfile.c"
  not_if { ::File.exists?("/usr/lib/httpd/modules/mod_xsendfile.so") }
end
