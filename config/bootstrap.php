<?php

if ($_SERVER['SERVER_NAME'] == 'backend.collectorsquest.dev' || $_SERVER['SERVER_NAME'] == 'backend.collectorsquest.next')
{
  define('SF_APP', 'backend');
  define('SF_ENV', 'dev');
  define('SF_DEBUG', true);
}
else if (false !== stripos($_SERVER['SERVER_NAME'], 'collectorsquest.dev'))
{
  define('SF_APP', 'legacy');
  define('SF_ENV', 'dev');
  define('SF_DEBUG', true);
}
else if (false !== stripos($_SERVER['SERVER_NAME'], 'collectorsquest.next') || $_SERVER['SERVER_NAME'] == '92.247.221.137' || $_SERVER['SERVER_NAME'] == 'zecho.dyndns-home.com')
{
  define('SF_APP', 'frontend');
  define('SF_ENV', 'dev');
  define('SF_DEBUG', true);
}
else if ($_SERVER['SERVER_NAME'] == 'backend.cqnext.com')
{
  define('SF_APP', 'backend');
  define('SF_ENV', 'next');
  define('SF_DEBUG', false);
}
else if (false !== stripos($_SERVER['SERVER_NAME'], 'cqnext.com'))
{
  define('SF_APP', 'frontend');
  define('SF_ENV', 'next');
  define('SF_DEBUG', false);
}
else if ($_SERVER['SERVER_NAME'] == 'backend.cqstaging.com')
{
  define('SF_APP', 'backend');
  define('SF_ENV', 'stg');
  define('SF_DEBUG', false);
}
else if (false !== stripos($_SERVER['SERVER_NAME'], 'cqstaging.com'))
{
  define('SF_APP', 'legacy');
  define('SF_ENV', 'stg');
  define('SF_DEBUG', false);
}
else if ($_SERVER['SERVER_NAME'] == 'backend.collectorsquest.com')
{
  define('SF_APP', 'backend');
  define('SF_ENV', 'prod');
  define('SF_DEBUG', false);
}
else
{
  define('SF_APP', 'legacy');
  define('SF_ENV', 'prod');
  define('SF_DEBUG', false);
}

if (SF_ENV === 'prod' && !defined('GIT_REVISION'))
{
  if (file_exists(dirname(__FILE__).'/../.git/FETCH_HEAD'))
  {
    $contents = (string) file_get_contents(dirname(__FILE__).'/../.git/FETCH_HEAD');
    define('GIT_REVISION', substr($contents, 0, stripos($contents, '		')));
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

require_once __DIR__ . '/ProjectConfiguration.class.php';
$configuration = ProjectConfiguration::getApplicationConfiguration(SF_APP, SF_ENV, SF_DEBUG);

// Start the page request timer
cqTimer::getInstance()->startTimer();
