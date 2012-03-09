# Run me with:
#
#   $ watchr apps/frontend/config/less.watchr

load "#{File.dirname(__FILE__)}/../../../lib/watchr.rb"

def watchr1
  crawl('web/less/frontend', 1, false) { |file_path, depth|
    if File.split( file_path )[ 1 ] =~ Regexp.new('^(?!_).*\.less$', true)
      plessc file_path, file_path.gsub('less', 'css')
    end
  }
end

def watchr2
  plessc 'web/less/frontend/bootstrap/less/bootstrap.less',
         'web/css/frontend/bootstrap.css'
  plessc 'web/less/frontend/bootstrap/less/responsive.less',
         'web/css/frontend/responsive.css'
end

# --------------------------------------------------
# On startup compiling
# --------------------------------------------------
watchr1()
watchr2()

# --------------------------------------------------
# Watchr Rules (put the more specific ones at the end of the list)
# --------------------------------------------------
watch ( 'web/less/frontend/.*\.less$' ) {
  watchr1
}

watch ( 'web/less/frontend/bootstrap/less/.*\.less$' ) {
  watchr2
}
