<?php

if(is_dir(dirname(__FILE__).'/../../../../lib/vendor/symfony/lib/'))
{
  $_SERVER['SYMFONY'] = realpath(dirname(__FILE__).'/../../../../lib/vendor/symfony/lib/');
}

if (!isset($_SERVER['SYMFONY']) || (isset($_SERVER['SYMFONY']) && !is_dir($_SERVER['SYMFONY'])))
{
  throw new RuntimeException('Could not find symfony core libraries.');
}

require_once $_SERVER['SYMFONY'].'/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

$_test_dir = realpath(dirname(__FILE__).'/..');

if (!isset($root_dir))
{
  $root_dir = realpath(dirname(__FILE__).sprintf('/../fixtures/project'));
}
require_once $root_dir.'/config/ProjectConfiguration.class.php';
$sf_configuration = ProjectConfiguration::hasActive() ? ProjectConfiguration::getActive() : new ProjectConfiguration(realpath($root_dir));

require_once $_SERVER['SYMFONY'].'/autoload/sfSimpleAutoload.class.php';
$autoload = sfSimpleAutoload::getInstance(sys_get_temp_dir().DIRECTORY_SEPARATOR.sprintf('sf_autoload_unit_cq_emails_plugin_%s.data', md5(__FILE__)));
$autoload->addDirectory(realpath(dirname(__FILE__).'/../../lib'));
$autoload->loadConfiguration(sfFinder::type('file')->name('autoload.yml')->in(array(
  sfConfig::get('sf_symfony_lib_dir').'/config/config',
  sfConfig::get('sf_config_dir'),
)));
$autoload->register();

include_once '/../../../../lib/vendor/twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

// Include Lime
include_once $sf_configuration->getSymfonyLibDir().'/vendor/lime/lime.php';

function test_cleanup_cache_log()
{
  @sfToolkit::clearGlob(dirname(__FILE__).'/../fixtures/project/cache/*');
  @sfToolkit::clearGlob(dirname(__FILE__).'/../fixtures/project/log/*');
}
test_cleanup_cache_log();
register_shutdown_function('test_cleanup_cache_log');

if (isset($app))
{
  $sf_application = $sf_configuration->getApplicationConfiguration($app, 'test', isset($debug) ? $debug : true);
  echo $sf_application->getRootDir();
  $sf_context = sfContext::createInstance($sf_application);
}