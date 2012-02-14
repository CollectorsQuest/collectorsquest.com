<?php

/**
 * Sends statistics to the stats daemon over UDP
 */
class cqStats extends IceStats
{
  /**
   * @var string The hostname of the StatsD server
   */
  const STATSD_HOST = 'cq-statsd';

  /**
   * @var integer The port of the StatsD server
   */
  const STATSD_PORT = 8125;

  /**
   * Increment the view count for a particular propel object
   *
   * To avoid many objects in the same folder (graphite stats are stored in folders)
   * we create an simple subfolder structure like this:
   *
   * Object with pk 69070: object.6.9.69070
   * Object with pk 9070:  object.0.9.9070
   * Object with pk 7:     object.0.0.7
   *
   * @param  BaseObject  $object The propel model object
   * @return boolean
   */
  public static function viewPropelObject(BaseObject $object)
  {
    if (method_exists($object, 'getPrimaryKey') && !is_array($pk = $object->getPrimaryKey()))
    {
      $path = sfInflector::underscore(get_class($object)) . '.';
      $path .= floor($pk / 10000) .'.'. floor(($pk % 10000) / 1000) .'.'. $pk;

      return self::view($path);
    }

    return false;
  }

  /**
   * @static
   *
   * @param  string  $path
   * @return boolean
   */
  public static function view($path)
  {
    // Increment the number of views
    return self::increment('collectorsquest.views.'. $path, 1);
  }
}
