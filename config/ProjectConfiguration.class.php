<?php

date_default_timezone_set('America/New_York');

define('SF_LIB_DIR', dirname(__FILE__).'/../lib/vendor/symfony/symfony1/lib/');

require_once __DIR__ .'/../lib/vendor/autoload.php';
require_once __DIR__.'/../plugins/iceLibsPlugin/lib/autoload/IceCoreAutoload.class.php';

IceCoreAutoload::register();

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
      'cqEmailsPlugin', 'cqMagnifyPlugin', 'iceSEOPlugin', 'cqErrorNotifierPlugin',
      'cqRatablePlugin'
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

    /**
     * For including WordPress functionality, we need to specify
     * both ABSPATH and WPINC
     */
    if (!defined('ABSPATH'))
    {
      define('ABSPATH', __DIR__ .'/../web/blog/');
      define('WPINC', 'wp-includes');
    }
  }

}
