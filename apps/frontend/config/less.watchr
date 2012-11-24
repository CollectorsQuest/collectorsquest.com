# Run me with:
#
#   $ watchr apps/frontend/config/less.watchr

load "#{File.dirname(__FILE__)}/../../../plugins/iceLibsPlugin/lib/watchr.rb"

$dev = false
single = nil

if ARGV.count == 2
  if 'dev' == ARGV[1]
    $dev = true
  else
    single = ARGV[1]
  end
end
if ARGV.count == 3
  single = ARGV[1]
  if 'dev' == ARGV[2]
      $dev = true
  end
end

if single != nil
  if File.basename(single) =~ Regexp.new('^(?!_).*', true)
   single = "/less/frontend/" + single + ".less"
  else
   puts 'Filename is wrong'
   exit
  end
end

def watchr1
  web = Pathname.new("#{File.dirname(__FILE__)}/../../../web").realpath.to_s

  crawl(web + "/less/frontend", 1, false) { |file_path, depth|
    if File.split(file_path)[ 1 ] =~ Regexp.new('^(?!_).*\.less$', true)
      lessc file_path, file_path.gsub('less', 'css'), web, !$dev
    end
  }
end

def watchr2
  web = Pathname.new("#{File.dirname(__FILE__)}/../../../web").realpath.to_s

  crawl(web + "/less/frontend/jquery", 1, false) { |file_path, depth|
    if File.split(file_path)[ 1 ] =~ Regexp.new('^(?!_).*\.less$', true)
      lessc file_path, file_path.gsub('less', 'css'), web, !$dev
    end
  }
end

def watchr3
  web = Pathname.new("#{File.dirname(__FILE__)}/../../../web").realpath.to_s

  crawl(web + "/less/frontend/modules", 1, false) { |file_path, depth|
    if File.split(file_path)[ 1 ] =~ Regexp.new('^(?!_).*\.less$', true)
      lessc file_path, file_path.gsub('less', 'css'), web, !$dev
    end
  }
end

def watchr4
  web = Pathname.new("#{File.dirname(__FILE__)}/../../../web").realpath.to_s

  less = web + "/less/frontend/bootstrap/bootstrap.less"
  css  = web + "/css/frontend/bootstrap.css"
  lessc less, css, web, !$dev
end

def watchr5(file_path)
  web = Pathname.new("#{File.dirname(__FILE__)}/../../../web").realpath.to_s

  if File.basename(file_path) =~ Regexp.new('^(?!_).*\.less$', true)
    lessc file_path, file_path.gsub('less', 'css'), web, !$dev
  end
end

# --------------------------------------------------
# On startup compiling
# --------------------------------------------------

if single != nil
  watchr5(Pathname.new("#{File.dirname(__FILE__)}/../../../web").realpath.to_s + single)
else
  watchr1()
  watchr2()
  watchr3()
  watchr4()
end

# --------------------------------------------------
# Watchr Rules
# --------------------------------------------------

if single != nil
  watch("web" + single) {|md|
    watchr5(Pathname.new("#{File.dirname(__FILE__)}/../../../web").realpath.to_s + single)
  }
else
  watch("web/less/frontend/.*\.less$") {|md|
    if File.basename(md.to_s) =~ Regexp.new('^(?!_).*\.less$', true)
      # update only one file
      watchr5(md.to_s)
    else
     # put the more specific rules to the end of the list
      watchr1
      watchr2
      watchr3
      watchr4
    end
  }
end
