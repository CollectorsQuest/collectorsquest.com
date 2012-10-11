<?php

date_default_timezone_set('America/New_York');

define('SF_LIB_DIR', dirname(__FILE__).'/../lib/vendor/symfony/lib/');
require_once __DIR__.'/../plugins/iceLibsPlugin/lib/autoload/IceCoreAutoload.class.php';
require_once __DIR__.'/../plugins/iceLibsPlugin/lib/autoload/IceClassLoader.class.php';

IceClassLoader::initialize();

/** Load the namespace for Neo4j client library */
IceClassLoader::getLoader()->registerNamespaces(array(
    'Everyman' => __DIR__ . '/../lib/vendor/neo4jphp/lib',
));
IceClassLoader::getLoader()->registerPrefixes(array(
    'Twig_' => __DIR__ . '/../lib/vendor/twig/lib'
));

class ProjectConfiguration extends sfProjectConfiguration
{

  protected $rocketShipItAutoloaderRegistered = false;

  public function setup()
  {
    parent::setup();

    setlocale(LC_ALL, 'en_US.utf8');

    iconv_set_encoding('input_encoding', 'UTF-8');
    iconv_set_encoding('output_encoding', 'UTF-8');
    iconv_set_encoding('internal_encoding', 'UTF-8');

    sfConfig::set('sf_phing_path', __DIR__.'/../plugins/sfPropelORMPlugin/lib/vendor/phing');
    sfConfig::set('sf_propel_path', __DIR__.'/../plugins/sfPropelORMPlugin/lib/vendor/propel');

    $this->enablePlugins('sfPropelORMPlugin', 'sfGuardPlugin');
    $this->enablePlugins(array(
      'iceAssetsPlugin', 'iceBehaviorsPlugin', 'iceLibsPlugin',
      'iceTaggablePlugin', 'iceJobQueuePlugin', 'iceCrontabPlugin',
      'iceSpamControlPlugin', 'iceGeoLocationPlugin', 'iceMultimediaPlugin',
      'cqEmailsPlugin', 'cqMagnifyPlugin', 'iceSEOPlugin', 'cqErrorNotifierPlugin'
    ));

    sfConfig::add(array(
      'sf_upload_dir' => '/www/vhosts/collectorsquest.com/shared/uploads',
      'sf_upload_dir_name' => 'uploads',
    ));

    if (!$this->rocketShipItAutoloaderRegistered)
    {
      require_once __DIR__ . '/../lib/vendor/rocketshipit/Autoloader.php';
      RocketShipItAutoloader::register();
    }
  }
}
