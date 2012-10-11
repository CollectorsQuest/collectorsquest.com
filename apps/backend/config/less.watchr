# Run me with:
#
#   $ watchr apps/backend/config/less.watchr

load "#{File.dirname(__FILE__)}/../../../plugins/iceLibsPlugin/lib/watchr.rb"

def watchr1
  web = Pathname.new("#{File.dirname(__FILE__)}/../../../web").realpath.to_s

  crawl(web + "/less/backend", 1, false) { |file_path, depth|
    if File.split(file_path)[ 1 ] =~ Regexp.new('^(?!_).*\.less$', true)
      lessc file_path, file_path.gsub('less', 'css'), web
    end
  }
end

def watchr2
  web = Pathname.new("#{File.dirname(__FILE__)}/../../../web").realpath.to_s

  crawl(web + "/less/backend/modules", 1, false) { |file_path, depth|
    if File.split(file_path)[ 1 ] =~ Regexp.new('^(?!_).*\.less$', true)
      lessc file_path, file_path.gsub('less', 'css'), web
    end
  }
end

def watchr3
  web = Pathname.new("#{File.dirname(__FILE__)}/../../../web").realpath.to_s

  crawl(web + "/less/backend/jquery", 1, false) { |file_path, depth|
    if File.split(file_path)[ 1 ] =~ Regexp.new('^(?!_).*\.less$', true)
      lessc file_path, file_path.gsub('less', 'css'), web
    end
  }
end

# --------------------------------------------------
# On startup compiling
# --------------------------------------------------
watchr1()
watchr2()
watchr3()

# --------------------------------------------------
# Watchr Rules (put the more specific ones at the end of the list)
# --------------------------------------------------

watch ("web/less/backend/.*\.less$") {
  watchr1
  watchr2
  watchr3
}
