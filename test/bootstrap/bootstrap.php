<?php

$_SERVER = array(
  'SCRIPT_URI'  => 'http://www.example.org',
  'HTTP_HOST'   => 'www.example.org',
  'SERVER_NAME' => 'www.example.org',
  'SCRIPT_NAME' => '/index.php',
  'PHP_SELF'    => '/index.php'
);

$_test_dir = realpath(__DIR__ .'/..');
$_root_dir = realpath($_test_dir . '/..');

define('SF_ENV', 'test');

// configuration
require_once $_root_dir . '/config/ProjectConfiguration.class.php';
$sf_configuration = ProjectConfiguration::hasActive() ?
  ProjectConfiguration::getActive() :
  new ProjectConfiguration(realpath($_root_dir));

// autoloader for sfPHPUnit2Plugin libs
$autoload = sfSimpleAutoload::getInstance(sfConfig::get('sf_cache_dir') . '/project_autoload.cache');
$autoload->loadConfiguration(sfFinder::type('file')->name('autoload.yml')->in(array(
  sfConfig::get('sf_symfony_lib_dir') . '/config/config',
  sfConfig::get('sf_config_dir'),
  $_root_dir . '/plugins/sfPHPUnit2Plugin/lib/config'
)));
$autoload->register();

// Include Lime
include_once $sf_configuration->getSymfonyLibDir().'/vendor/lime/lime.php';

register_shutdown_function(function(){
  sfToolkit::clearDirectory(sfConfig::get('sf_cache_dir'));
  touch(sfConfig::get('sf_cache_dir').'/.ignore');
});
