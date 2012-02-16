<?php

include __DIR__ .'/model.php';

require_once __DIR__ .'/../../lib/test/cqWebTestResponse.class.php';
require_once __DIR__ .'/../../lib/test/cqWebTestRequest.class.php';

// remove all cache
sfToolkit::clearDirectory(sfConfig::get('sf_app_cache_dir'));
