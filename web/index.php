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

/**
 * @var cqApplicationConfiguration $configuration
 */
require dirname(__FILE__) .'/../config/bootstrap.php';
sfContext::createInstance($configuration)->dispatch();

// Time the request and send it to Graphite
cqStats::timing('collectorsquest.response', cqTimer::getInstance()->getElapsedTime());

/**
 * Track requests from the top search spiders to statsd
 */
$spiders = array(
  'googlebot' => "/\.googlebot\.com$/i",
  'msnbot' => "/search\.live\.com$/i",
  'yahoo' => "/\.yahoo\.com$/i"
);

foreach ($spiders as $name => $pattern)
{
  if (isset($_SERVER['HTTP_USER_AGENT']) && stristr($_SERVER['HTTP_USER_AGENT'], $name))
  {
    $ip = cqStatic::getUserIpAddress();
    $hostname = gethostbyaddr($ip);

    if (preg_match($pattern, $hostname))
    {
      // Now we have a hit that half-passes the check. One last go:
      $real_ip = gethostbyname($hostname);

      if ($ip == $real_ip)
      {
        cqStats::increment('collectorsquest.crawlers.'. $name);
      }
    }

    break;
  }
}

/**
 * Record the XHProf run only if the page execution time is greater than 1 second
 */
if ($_xhprof_on && ((isset($_GET['_profile']) && $_GET['_profile'] == '1') || (2 < microtime(true) - $_time_start)))
{
  // stop profiler
  $_xhprof_data = xhprof_disable();

  include __DIR__ ."/../config/xhprof.php";
  include __DIR__ ."/../plugins/iceLibsPlugin/lib/vendor/xhprof/xhprof_lib.php";
  include __DIR__ ."/../plugins/iceLibsPlugin/lib/vendor/xhprof/xhprof_runs.php";

  $_xhprof_runs = new XHProfRuns_Default();
  $_xhprof_run = $_xhprof_runs->save_run($_xhprof_data, 'autohop_'. SF_APP);
}
