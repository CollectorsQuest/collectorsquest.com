# Run me with:
#
#   $ watchr apps/frontend/config/less.watchr

load "#{File.dirname(__FILE__)}/../../../plugins/iceLibsPlugin/lib/watchr.rb"

def watchr1
  web = Pathname.new("#{File.dirname(__FILE__)}/../../../web").realpath.to_s

  crawl(web + "/less/frontend", 1, false) { |file_path, depth|
    if File.split(file_path)[ 1 ] =~ Regexp.new('^(?!_).*\.less$', true)
      lessc file_path, file_path.gsub('less', 'css'), web
    end
  }
end

def watchr2
  web = Pathname.new("#{File.dirname(__FILE__)}/../../../web").realpath.to_s

  less = web + "/less/frontend/bootstrap/less/bootstrap.less"
  css  = web + "/css/frontend/bootstrap.css"
  lessc less, css, web

  less = web + "/less/frontend/bootstrap/less/responsive.less"
  css  = web + "/css/frontend/responsive.css"
  lessc less, css, web
end

# --------------------------------------------------
# On startup compiling
# --------------------------------------------------
watchr1()
watchr2()

# --------------------------------------------------
# Watchr Rules (put the more specific ones at the end of the list)
# --------------------------------------------------

watch ("web/less/frontend/.*\.less$") {
  watchr1
  watchr2
}
