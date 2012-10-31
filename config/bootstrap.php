<?php

// Set the correct timezone and do not rely on php.ini settings
date_default_timezone_set('America/New_York');

$app = isset($_SERVER['SF_APP']) ? (string) $_SERVER['SF_APP'] : 'frontend';
$env = isset($_SERVER['SF_ENV']) ? (string) $_SERVER['SF_ENV'] : 'prod';
$dbg = isset($_SERVER['SF_DEBUG']) ? (boolean) $_SERVER['SF_DEBUG'] : $env === 'dev';

/**
 * Special case for when we want to access the backend application via web/backend.php
 */
if (trim($_SERVER['SCRIPT_NAME'], '/') === 'backend.php')
{
  $app = 'backend';
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

    if (empty($git) || (isset($success) && $success !== true))
    {
      $git = `git rev-parse HEAD`;
      $git = trim($git);

      if (strlen($git) !== 40)
      {
        $git = sha1(uniqid('git_revision_', true));
      }
      if (function_exists('apc_store'))
      {
        apc_store('GIT_REVISION', $git, 60);
      }
    }
  }
  else
  {
    $git = sha1(uniqid('git_revision_', true));
  }

  $git = substr($git, 0, 7);
  $svn = sprintf('%u', crc32($git));

  define('GIT_REVISION', $git);
  define('SVN_REVISION', $svn);

  // Cleanup
  unset($git, $svn, $success);
}
