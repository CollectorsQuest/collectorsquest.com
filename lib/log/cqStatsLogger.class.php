<?php

/**
 * A central class to hold all statsd logging
 *
 */
class cqStatsLogger
{

  /**
   * Increment the view count for a particular propel object
   *
   * To avoid many objects in the same folder (graphite stats are stored in folders)
   * we create an simple subfolder structure like this:
   *
   * Object with pk 69070: collectorsquest.views.object.6.9.69070
   * Object with pk 9070:  collectorsquest.views.object.0.9.9070
   * Object with pk 7:     collectorsquest.views.object.0.0.7
   *
   * @param       BaseObject $object The propel model object
   */
  public static function viewPropelObject(BaseObject $object)
  {
    $base = 'collectorsquest.views.';

    if (method_exists($object, 'getPrimaryKey') && !is_array($pk = $object->getPrimaryKey()))
    {
      $path = $base . sfInflector::underscore(get_class($object)) . '.';
      $path .= floor($pk / 10000) .'.'. floor(($pk % 10000) / 1000) .'.'. $pk;

      // Increment the number of views
      cqStats::increment($path);
    }
  }

}