<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

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
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define('WP_USE_THEMES', true);

/** Loads the WordPress Environment and Template */
require('./wp-blog-header.php');


/**
 * Record the XHProf run only if the page execution time is greater than 2 seconds
 */
if ($_xhprof_on && ((isset($_GET['_profile']) && $_GET['_profile'] == '1') || (2 < microtime(true) - $_time_start)))
{
  // stop profiler
  $_xhprof_data = xhprof_disable();

  include __DIR__ ."/../../config/xhprof.php";
  include __DIR__ ."/../../plugins/iceLibsPlugin/lib/vendor/xhprof/xhprof_lib.php";
  include __DIR__ ."/../../plugins/iceLibsPlugin/lib/vendor/xhprof/xhprof_runs.php";

  $_xhprof_runs = new XHProfRuns_Default();
  $_xhprof_run = $_xhprof_runs->save_run($_xhprof_data, 'collectorsquest_blog');
}
