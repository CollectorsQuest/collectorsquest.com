# Run me with:
#
#   $ watchr apps/frontend/config/less.watchr

# --------------------------------------------------
# Helpers
# --------------------------------------------------
def crawl( path, max_depth=nil, include_directories=false, depth=0, &block )
 return if max_depth && depth > max_depth
 begin
   if File.directory?(path)
     yield(path, depth) if include_directories
     files = Dir.entries(path).select{ |f| true unless f = ~/^\.{1,2}$/ }
     unless files.empty?
       files.collect!{ |file_path|
         crawl( path +'/'+ file_path, max_depth, include_directories, depth + 1, &block )
       }.flatten!
     end
     return files
   else
     yield(path, depth)
   end
 rescue SystemCallError => the_error
   warn "ERROR: #{the_error}"
 end
end

def lessc(input, output)
  print "[" + Time.now.strftime("%I:%M:%S") + "] compiling #{input.inspect}... "
  system "console/plessc #{input} #{output}"
  puts 'done'
end

# --------------------------------------------------
# Watchr Rules (put the more specific ones at the end of the list)
# --------------------------------------------------
watch ( 'web/less/frontend/.*\.less$' ) {
  crawl('web/less/frontend', 1, false){ |file_path, depth|
    if File.split( file_path )[ 1 ] =~ Regexp.new('^(?!_).*\.less$', true)
      lessc file_path, file_path.gsub('less', 'css')
    end
  }
}

watch ( 'web/less/frontend/bootstrap/less/.*\.less$' ) {
  lessc 'web/less/frontend/bootstrap/less/bootstrap.less',
        'web/css/frontend/bootstrap.css'
}

# --------------------------------------------------
# Signal Handling
# --------------------------------------------------
# Ctrl-\
Signal.trap('QUIT') do
  puts " --- Compiling all .less files ---\n\n"
  Dir['**/*.less'].each {|file| lessc file }
  puts 'all compiled'
end

# Ctrl-C
Signal.trap('INT')  { abort("\n") }
