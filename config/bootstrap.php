<?php

$app = isset($_SERVER['SF_APP']) ? $_SERVER['SF_APP'] : 'frontend';
$env = isset($_SERVER['SF_ENV']) ? $_SERVER['SF_ENV'] : 'prod';
$dbg = isset($_SERVER['SF_DEBUG']) ? (boolean) $_SERVER['SF_DEBUG'] : false;

if ($_SERVER['SERVER_NAME'] == 'backend.collectorsquest.dev')
{
  $app = 'backend';
  $env = 'dev';
  $dbg = true;
}
else if (
  false !== stripos($_SERVER['SERVER_NAME'], 'collectorsquest.dev') ||
  $_SERVER['SERVER_NAME'] == '92.247.236.83' ||
  $_SERVER['SERVER_NAME'] == 'zecho.dyndns-home.com'
) {
  $app = 'frontend';
  $env = 'dev';
  $dbg = true;
}
else if ($_SERVER['SERVER_NAME'] == 'backend.cqnext.com')
{
  $app = 'backend';
  $env = 'next';
  $dbg = false;
}
else if (false !== stripos($_SERVER['SERVER_NAME'], 'cqnext.com'))
{
  $app = 'frontend';
  $env = 'next';
  $dbg = false;
}
else if ($_SERVER['SERVER_NAME'] == 'backend.cqstaging.com')
{
  $app = 'backend';
  $env = 'stg';
  $dbg = false;
}
else if (false !== stripos($_SERVER['SERVER_NAME'], 'cqstaging.com'))
{
  $app = 'disabled';
  $env = 'stg';
  $dbg = false;
}
else if ($_SERVER['SERVER_NAME'] == 'backend.collectorsquest.com')
{
  $app = 'backend';
  $env = 'prod';
  $dbg = false;
}
else
{
  $app = 'frontend';
  $env = 'prod';
  $dbg = false;
}

if (isset($_COOKIE['sf_debug']) && $_COOKIE['sf_debug'] == '1')
{
  $env = $env .'_debug';
  $dbg = true;
}

define('SF_APP', $app);
define('SF_ENV', $env);
define('SF_DEBUG', $dbg);

// Cleanup
unset($app, $env, $dbg);

if (SF_ENV === 'prod' && !defined('GIT_REVISION'))
{
  if ($hash = `git rev-parse HEAD`)
  {
    define('GIT_REVISION', substr(trim($hash), 0, 40));
    define('SVN_REVISION', sprintf("%u", crc32(GIT_REVISION)));
  }
  else
  {
    define('GIT_REVISION', 1);
    define('SVN_REVISION', 1);
  }
}
else if (!defined('GIT_REVISION'))
{
  define('GIT_REVISION', md5(uniqid('git_revision_', true)));
  define('SVN_REVISION', rand(1, PHP_INT_MAX));
}
