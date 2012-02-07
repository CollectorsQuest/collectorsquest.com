<?php

date_default_timezone_set('America/New_York');

define('SF_LIB_DIR', dirname(__FILE__).'/../lib/vendor/symfony/lib/');
require_once __DIR__.'/../plugins/iceLibsPlugin/lib/autoload/IceCoreAutoload.class.php';
require_once __DIR__.'/../plugins/iceLibsPlugin/lib/autoload/SplClassLoader.class.php';
IceCoreAutoload::register();

/** Load the namespace for Neo4j client library */
$classLoader = new SplClassLoader('Everyman', __DIR__ . '/../lib/vendor/neo4jphp/lib');
$classLoader->register();

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
      'iceAssetsPlugin', 'iceBehaviorsPlugin', 'iceLibsPlugin', 'iceTaggablePlugin',
      'iceBackendPlugin', 'iceJobQueuePlugin', 'iceCrontabPlugin', 'iceSpamControlPlugin'
    );

    sfConfig::add(array(
      'sf_upload_dir' => '/www/vhosts/collectorsquest.com/shared/uploads',
      'sf_upload_dir_name' => 'uploads',
    ));

  }
}
