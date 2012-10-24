<?php


/**
 * Skeleton subclass for representing a row from the 'multimedia' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.lib.model.plugins.iceMultimediaPlugin
 */
class iceModelMultimedia extends PluginiceModelMultimedia
{

  /**
   * @param     string|array $colors
   * @return    iceModelMultimedia
   */
  public function setColors($colors)
  {
    if (is_array($colors))
    {
      $colors = implode(', ', $colors);
    }

    return parent::setColors($colors);
  }

  /**
   * @return    array
   */
  public function getColors()
  {
    $colors = explode(', ', parent::getColors());
    $colors = array_filter($colors);

    return $colors;
  }

}
