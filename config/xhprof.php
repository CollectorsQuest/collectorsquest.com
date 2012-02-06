<?php

date_default_timezone_set('America/New_York');

$_xhprof = array();

if ($_SERVER['SF_ENV'] == 'dev')
{
  $_xhprof['dbhost'] = '127.0.0.1';
  $_xhprof['dbuser'] = 'root';
  $_xhprof['dbpass'] = '';
  $_xhprof['dbname'] = 'collectorsquest_dev';
}
else if (is_readable(__DIR__.'/secure/xhprof.php'))
{
  include_once __DIR__.'/secure/xhprof.php';
}

//These are good for linux and its derivatives.
$_xhprof['dot_binary'] = '/usr/bin/dot';
$_xhprof['dot_tempdir'] = '/tmp';
$_xhprof['dot_errfile'] = '/tmp/xh_dot.err';

$exceptionURLs = array();
$exceptionPostURLs = array();

$_xhprof['display'] = false;
$_xhprof['doprofile'] = false;

/**
 * The goal of this function is to accept the URL for a resource, and return a "simplified" version
 * thereof. Similar URLs should become identical. Consider:
 * http://example.org/stories.php?id=2323
 * http://example.org/stories.php?id=2324
 * Under most setups these two URLs, while unique, will have an identical execution path, thus it's
 * worthwhile to consider them as identical. The script will store both the original URL and the
 * Simplified URL for display and comparison purposes. A good simplified URL would be:
 * http://example.org/stories.php?id=
 *
 * @param string $url The URL to be simplified
 * @return string The simplified URL
 */
function _urlSimilartor($url)
{
  //This is an example
  $url = preg_replace("!\d{4}!", "", $url);

  if ($similartorinclude = getenv('xhprof_urlSimilartor_include'))
  {
    require_once($similartorinclude);
  }

  $url = preg_replace("![?&]_profile=\d!", "", $url);
  return $url;
}

function _aggregateCalls($calls, $rules = null)
{
  $rules = array(
    'Loading' => 'load::',
    'mysql' => 'mysql_'
  );

  if (isset($run_details['aggregateCalls_include']) && strlen($run_details['aggregateCalls_include']) > 1)
  {
    require_once($run_details['aggregateCalls_include']);
  }

  $addIns = array();
  foreach ($calls as $index => $call)
  {
    foreach ($rules as $rule => $search)
    {
      if (strpos($call['fn'], $search) !== false)
      {
        if (isset($addIns[$search]))
        {
          unset($call['fn']);
          foreach ($call as $k => $v)
          {
            $addIns[$search][$k] += $v;
          }
        }
        else
        {
          $call['fn'] = $rule;
          $addIns[$search] = $call;
        }

        unset($calls[$index]); //Remove it from the listing
        break; //We don't need to run any more rules on this
      }
      else
      {
        // echo "nomatch for $search in {$call['fn']}<br />\n";
      }
    }
  }

  return array_merge($addIns, $calls);
}
