# Run me with:
#
#   $ watchr apps/backend/config/less.watchr

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
   single = "/less/backend/" + single + ".less"
  else
   puts 'Filename is wrong'
   exit
  end
end
def watchr1
  web = Pathname.new("#{File.dirname(__FILE__)}/../../../web").realpath.to_s

  crawl(web + "/less/backend", 1, false) { |file_path, depth|
    if File.split(file_path)[ 1 ] =~ Regexp.new('^(?!_).*\.less$', true)
      lessc file_path, file_path.gsub('less', 'css'), web, !$dev
    end
  }
end

def watchr2
  web = Pathname.new("#{File.dirname(__FILE__)}/../../../web").realpath.to_s

  crawl(web + "/less/backend/modules", 1, false) { |file_path, depth|
    if File.split(file_path)[ 1 ] =~ Regexp.new('^(?!_).*\.less$', true)
      lessc file_path, file_path.gsub('less', 'css'), web, !$dev
    end
  }
end

def watchr3(file_path)
  web = Pathname.new("#{File.dirname(__FILE__)}/../../../web").realpath.to_s

  if File.basename(file_path) =~ Regexp.new('^(?!_).*\.less$', true)
    lessc file_path, file_path.gsub('less', 'css'), web, !$dev
  end
end

# --------------------------------------------------
# On startup compiling
# --------------------------------------------------
if single != nil
  watchr3(Pathname.new("#{File.dirname(__FILE__)}/../../../web").realpath.to_s + single)
else
  watchr1()
  watchr2()
end

# --------------------------------------------------
# Watchr Rules
# --------------------------------------------------

if single != nil
  watch("web" + single) {|md|
    watchr3(Pathname.new("#{File.dirname(__FILE__)}/../../../web").realpath.to_s + single)
  }
else
  watch("web/less/backend/.*\.less$") {|md|
    if File.basename(md.to_s) =~ Regexp.new('^(?!_).*\.less$', true)
      # update only one file
      watchr3(md.to_s)
    else
     # put the more specific rules to the end of the list
      watchr1
      watchr2
    end
  }
end

