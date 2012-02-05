<?php

require_once(dirname(__FILE__).'/ProjectConfiguration.class.php');

if (!defined('SVN_REVISION'))
{
  if (file_exists(dirname(__FILE__).'/../REVISION'))
  {
    define('SVN_REVISION', (int) file_get_contents(dirname(__FILE__).'/../REVISION'));
  }
  else
  {
    define('SVN_REVISION', 1);
  }
}

if ($_SERVER['SERVER_NAME'] == 'www.collectorsquest.dev' || $_SERVER['SERVER_NAME'] == 'collectorsquest.dev')
{
  define('SF_APP', 'legacy');
  define('SF_ENV', 'dev');
  define('SF_DEBUG', true);
}
else if ($_SERVER['SERVER_NAME'] == 'backend.collectorsquest.dev')
{
  define('SF_APP', 'backend');
  define('SF_ENV', 'dev');
  define('SF_DEBUG', true);
}
else if ($_SERVER['SERVER_NAME'] == 'backend.collectorsquest.com')
{
  define('SF_APP', 'backend');
  define('SF_ENV', 'prod');
  define('SF_DEBUG', false);
}
else if ($_SERVER['SERVER_NAME'] == 'www.collectorsquest.stg')
{
  define('SF_APP', 'legacy');
  define('SF_ENV', 'stg');
  define('SF_DEBUG', true);
}
else
{
  define('SF_APP', 'legacy');
  define('SF_ENV', 'prod');
  define('SF_DEBUG', false);
}

$configuration = ProjectConfiguration::getApplicationConfiguration(SF_APP, SF_ENV, SF_DEBUG);

// Start the page request timer
cqTimer::getInstance()->startTimer();
