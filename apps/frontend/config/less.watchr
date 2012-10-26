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

  crawl(web + "/less/frontend/jquery", 1, false) { |file_path, depth|
    if File.split(file_path)[ 1 ] =~ Regexp.new('^(?!_).*\.less$', true)
      lessc file_path, file_path.gsub('less', 'css'), web
    end
  }
end

def watchr3
  web = Pathname.new("#{File.dirname(__FILE__)}/../../../web").realpath.to_s

  crawl(web + "/less/frontend/modules", 1, false) { |file_path, depth|
    if File.split(file_path)[ 1 ] =~ Regexp.new('^(?!_).*\.less$', true)
      lessc file_path, file_path.gsub('less', 'css'), web
    end
  }
end

def watchr4
  web = Pathname.new("#{File.dirname(__FILE__)}/../../../web").realpath.to_s

  less = web + "/less/frontend/bootstrap/bootstrap.less"
  css  = web + "/css/frontend/bootstrap.css"
  lessc less, css, web
end

# --------------------------------------------------
# On startup compiling
# --------------------------------------------------
watchr1()
#watchr2()
watchr3()
watchr4()

# --------------------------------------------------
# Watchr Rules (put the more specific ones at the end of the list)
# --------------------------------------------------

watch ("web/less/frontend/.*\.less$") {
  watchr1
# watchr2
  watchr3
  watchr4
}
