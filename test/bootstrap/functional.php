<?php

include(__DIR__ .'/model.php');

// remove all cache
sfToolkit::clearDirectory(sfConfig::get('sf_app_cache_dir'));
