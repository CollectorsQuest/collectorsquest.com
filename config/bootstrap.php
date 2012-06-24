<?php

$app = isset($_SERVER['SF_APP']) ? (string) $_SERVER['SF_APP'] : 'frontend';
$env = isset($_SERVER['SF_ENV']) ? (string) $_SERVER['SF_ENV'] : 'prod';
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

if (!defined('GIT_REVISION'))
{
  if (SF_ENV === 'prod')
  {
    if (function_exists('apc_fetch'))
    {
      $success = false;
      $git = apc_fetch('GIT_REVISION', $success);
    }

    if (empty($git) || $success !== true)
    {
      $git = `git rev-parse HEAD`;
      $git = trim($git);

      if (strlen($git) !== 40) {
        $git = sha1(uniqid('git_revision_', true));
      }

      if (function_exists('apc_store')) {
        apc_store('GIT_REVISION', $git, 60);
      }
    }
  }
  else
  {
    $git = sha1(uniqid('git_revision_', true));
  }

  $git = substr($git, 0, 7);
  $svn = sprintf("%u", crc32($git));

  define('GIT_REVISION', $git);
  define('SVN_REVISION', $svn);

  // Cleanup
  unset($git, $svn, $success);
}
