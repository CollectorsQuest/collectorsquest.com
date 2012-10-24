<?php


require 'lib/model/plugins/iceMultimediaPlugin/om/BaseiceModelMultimediaPeer.php';


/**
 * Skeleton subclass for performing query and update operations on the 'multimedia' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model.plugins.iceMultimediaPlugin
 */
class iceModelMultimediaPeer extends PluginiceModelMultimediaPeer
{

  const RANGE_REDS = 'reds';
  const RANGE_WHITES = 'whites';
  const RANGE_BLUES = 'blues';

  public static $color_table = array(
      self::RANGE_REDS => array(
        '#FF0000', '#B51315', '#8A1D17', '#B74056', '#E22D63', '#A52A2A'
      ),
      self::RANGE_WHITES => array(
        '#FFFFFF', '#F6FEF4', '#E3E0BD', '#E0F9F1',
      ),
      self::RANGE_BLUES => array(
        '#0000ff', '#0524B2', '#1D5376',
      ),
  );


}
