<?php

date_default_timezone_set('America/New_York');

define('SF_LIB_DIR', dirname(__FILE__).'/../lib/vendor/symfony/lib/');
require_once __DIR__.'/../plugins/iceLibsPlugin/lib/autoload/IceCoreAutoload.class.php';
require_once __DIR__.'/../plugins/iceLibsPlugin/lib/autoload/IceClassLoader.class.php';

IceClassLoader::initialize();

/** Load the namespace for Neo4j client library */
IceClassLoader::getLoader()->registerNamespaces(array(
  'Everyman' => __DIR__ . '/../lib/vendor/neo4jphp/lib',
  'Monolog'  => __DIR__ . '/../lib/vendor/monolog/src',
));

class ProjectConfiguration extends IceProjectConfiguration
{
  public function setup()
  {
    setlocale(LC_ALL, 'en_US.utf8');

    iconv_set_encoding('input_encoding', 'UTF-8');
    iconv_set_encoding('output_encoding', 'UTF-8');
    iconv_set_encoding('internal_encoding', 'UTF-8');

    $this->enablePlugins('sfPropelORMPlugin', 'sfGuardPlugin');
    $this->enablePlugins(
      'iceAssetsPlugin', 'iceBehaviorsPlugin', 'iceLibsPlugin',
      'iceTaggablePlugin', 'iceBackendPlugin', 'iceJobQueuePlugin',
      'iceCrontabPlugin', 'iceSpamControlPlugin', 'iceGeoLocationPlugin'
    );

    sfConfig::add(array(
      'sf_upload_dir' => '/www/vhosts/collectorsquest.com/shared/uploads',
      'sf_upload_dir_name' => 'uploads',
    ));

  }
}
