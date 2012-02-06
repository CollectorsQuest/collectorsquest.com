maintainer        "Collectors' Quest, Inc."
maintainer_email  "developers@collectorsquest.com"
description       "Chef recipies entry point"
version           "1.0.0"

recipe "main", "Chef recipies entry point"

%w{ubuntu}.each do |os|
  supports os
end
