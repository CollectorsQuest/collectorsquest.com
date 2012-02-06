maintainer        "Collectors' Quest, Inc."
maintainer_email  "developers@collectorsquest.com"
description       "Installs development packages/settings"
version           "1.0.0"

recipe "development", "Installs development packages/settings"

%w{ubuntu}.each do |os|
  supports os
end
