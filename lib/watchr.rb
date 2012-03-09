# --------------------------------------------------
# Watchr Helpers
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

def plessc(input, output)
  print "[" + Time.now.strftime("%I:%M:%S") + "] compiling #{input.inspect}... "
  system "php console/plessc #{input} #{output}"
  puts 'done'
end
