package "vim"

template "/root/.vimrc" do
  source "vimrc.erb"
  mode "0664"
end

directory "/root/.vim/autoload" do
  owner "root"
  group "root"
  mode "0755"
  action :create
  recursive true
end

directory "/root/.vim/bundle" do
  owner "root"
  group "root"
  mode "0755"
  action :create
  recursive true
end

remote_file "/root/.vim/autoload/pathogen.vim" do
  source "https://raw.github.com/tpope/vim-pathogen/HEAD/autoload/pathogen.vim"
  mode "0664"
end
