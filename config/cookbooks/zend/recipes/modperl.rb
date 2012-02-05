package "mod_perl"
package "perl-CPAN"

execute "Install Digest::HMAC_SHA1" do
  command "/usr/bin/cpan Digest::HMAC_SHA1"
end
