<?php

class FindsSellersAnonymous
{
  const IS_OFFENDING_REGEX = '/((?:\$|£|€)\s*\d+|(?:shipping|(?<!not\s)(for sale|selling)))/iu';

  /**
   * Find offending collectibles
   *
   * Return is an array indexed by the collectible id and containing an array of
   * all offending values for that collectible
   *
   * @param array $collectibles
   * @return array
   */
  public static function forCollectibles($collectibles)
  {
    return static::forObjects($collectibles, array('name', 'description'));
  }

  /**
   * Find offending collections
   *
   * Return is an array indexed by the collector id and containing an array of
   * all offending values for that collector
   *
   * @param array $collections
   * @return array
   */
  public static function forCollections($collections)
  {
    return static::forObjects($collections, array('name', 'description'));
  }

  /**
   * Find offending collectors
   *
   * Return is an array indexed by the collector id and containing an array of
   * all offending values for that collector
   *
   * @param array $collectors
   * @return array
   */
  public static function forCollectors($collectors)
  {
    return static::forObjects($collectors, array('display_name'));
  }

  /**
   * Find offending objects  :)
   *
   * Will check the getters for the supplied fields against
   * FindsSellersAnonymous::isOffending and
   *
   * Expect passed in objects to have a getPrimaryKey method that returns
   * a scalar value
   *
   * @param BaseObject[] $objects
   * @param array $fields
   *
   * @return array
   */
  public static function forObjects($objects, $fields)
  {
    $output = array();
    foreach ($objects as $object)
    {
      foreach ($fields as $field)
      {
        $string = $object->{'get' . sfInflector::camelize($field)}();
        if (static::isOffending($string))
        {
          $output[$object->getPrimaryKey()]['object'] = $object;
          $output[$object->getPrimaryKey()]['offending_strings'][] = $string;
        }
      }
    }

    return $output;
  }

  /**
   * Check if a particular string is an offender
   *
   * @param     string $string
   * @return    boolean
   */
  public static function isOffending($string)
  {
    return preg_match(self::IS_OFFENDING_REGEX, $string);
  }

}
