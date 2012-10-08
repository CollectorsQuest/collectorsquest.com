<?php

$_time_start = microtime(true);

if (extension_loaded('xhprof') && ((isset($_GET['_profile']) && $_GET['_profile'] == '1') || mt_rand(1, 1000) == 1))
{
  xhprof_enable(
    XHPROF_FLAGS_CPU | XHPROF_FLAGS_NO_BUILTINS | XHPROF_FLAGS_MEMORY,
    array('ignored_functions' =>  array('call_user_func', 'call_user_func_array'))
  );

  $_xhprof_on = true;
}
else
{
  $_xhprof_on = false;
}

require __DIR__ .'/../config/bootstrap.php';
require __DIR__ .'/../config/ProjectConfiguration.class.php';

/** @var cqApplicationConfiguration $configuration */
$configuration = ProjectConfiguration::getApplicationConfiguration(SF_APP, SF_ENV, SF_DEBUG);

// Start the page request timer
cqTimer::getInstance()->startTimer();

// Handle the request
sfContext::createInstance($configuration, null, 'cqContext')->dispatch();

// Time the request and send it to Graphite
cqStats::timing('collectorsquest.response', cqTimer::getInstance()->getElapsedTime());

// Tracking if the request is from a Search Engine
if (false !== $crawler = cqStatic::isCrawler())
{
  cqStats::increment('collectorsquest.crawlers.'. $crawler);
}

/**
 * Record the XHProf run only if the page execution time is greater than 2 seconds
 */
if ($_xhprof_on && ((isset($_GET['_profile']) && $_GET['_profile'] == '1') || (2 < microtime(true) - $_time_start)))
{
  // stop profiler
  $_xhprof_data = xhprof_disable();

  include __DIR__ .'/../config/xhprof.php';
  include __DIR__ .'/../plugins/iceLibsPlugin/lib/vendor/xhprof/xhprof_lib.php';
  include __DIR__ .'/../plugins/iceLibsPlugin/lib/vendor/xhprof/xhprof_runs.php';

  $_xhprof_runs = new XHProfRuns_Default();
  $_xhprof_runs->save_run($_xhprof_data, 'collectorsquest_'. SF_APP);
}
