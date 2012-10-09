<?php


require 'lib/model/om/BaseCollectibleRatePeer.php';


/**
 * Skeleton subclass for performing query and update operations on the 'collectible_rates' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model
 */
class CollectibleRatePeer extends BaseCollectibleRatePeer {
  //TO DO move it to Rate behavior
  const MAX_RATE = 6;
  private static $dimensions = array(
    'content' => 'Content',
    'images' => 'Image(s)',
  );

  public static function getRateChoices()
  {
    return array_combine(range(1, self::MAX_RATE), range(1, self::MAX_RATE));
  }

  public static function getDimensions()
  {
    return self::$dimensions;
  }
}
