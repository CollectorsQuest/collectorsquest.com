<?php

include __DIR__ .'/model.php';

require_once __DIR__ .'/../../lib/test/KolkoWebTestResponse.class.php';
require_once __DIR__ .'/../../lib/test/KolkoWebTestRequest.class.php';

// remove all cache
sfToolkit::clearDirectory(sfConfig::get('sf_app_cache_dir'));
