#
# Cookbook Name:: zend
# Recipe:: default
#
# Copyright 2011, Heavy Water Software Inc.
#
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#
#     http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.
#

package "php-5.3-memcache-zend-server"
package "php-5.3-imagick-zend-server"
package "php-5.3-thrift-zend-server"

link "/usr/bin/php" do
  to "/usr/local/zend/bin/php"
  not_if "test -L /usr/bin/php"
end

directory "/usr/local/zend/var/apps/http/collectorsquest.com" do
  recursive true
  owner "zend"
  group "zend"
  mode "0755"
  action :create
end

link "/usr/local/zend/var/apps/http/collectorsquest.com/80" do
  to "/www/vhosts/collectorsquest.com/releases"
  not_if "test -L /usr/local/zend/var/apps/http/collectorsquest.com/80"
end

service "zend" do
  service_name "zend-server"
  supports :status => true, :restart => true
  action [ :enable, :start ]
end

service "apache" do
  service_name "httpd"
  supports :status => true, :restart => true
  action [ :enable, :start ]
end
