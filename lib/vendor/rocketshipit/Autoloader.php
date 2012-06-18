<?php

/**
 * Autoloads Rocket Ship It classes
 */
class RocketShipItAutoloader
{

  protected static $loaded = false;

  /**
   * Registers RocketShipItAutoloader as an spl autoloader
   */
  static public function register()
  {
    spl_autoload_register(array(new self, 'autoload'));
  }

  /**
   * Handles autoloading of classes.
   *
   * @param  string  $class  A class name.
   *
   * @return boolean Returns true if the class has been loaded
   */
  public static function autoload($class)
  {
    if (!self::$loaded)
    {
      $rockShipItClasses = array(
          'RocketShipTrack',
          'RocketShipRate',
          'RocketShipTimeInTransit',
          'RocketShipPackage',
          'RocketShipAddressValidate',
          'RocketShipQueue',
      );

      if (in_array($class, $rockShipItClasses))
      {
        require_once __DIR__ . '/RocketShipItRandT.php';
      }
    }
  }

}
