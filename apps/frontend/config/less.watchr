# Run me with:
#
#   $ watchr apps/frontend/config/less.watchr

# --------------------------------------------------
# Helpers
# --------------------------------------------------
def lessc(input, output)
  print "compiling #{input.inspect}... "
  system "console/plessc #{input} #{output}"
  puts 'done'
end

# --------------------------------------------------
# Watchr Rules
# --------------------------------------------------
watch ( 'web/less/frontend/bootstrap/less/.*\.less$' ) {
  lessc 'web/less/frontend/bootstrap/less/bootstrap.less',
        'web/css/frontend/bootstrap.css'
}

watch ( 'web/less/frontend/(?!_).*\.less$' ) {
  |md| lessc md[0], md[0].gsub!('less', 'css')
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
