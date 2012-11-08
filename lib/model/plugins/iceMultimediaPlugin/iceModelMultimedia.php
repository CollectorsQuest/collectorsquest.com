<?php

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
   * @param     string  $return
   * @return    array
   */
  public function getColors($return = 'string')
  {
    $colors = parent::getColors();

    if ($return === 'array')
    {
      $colors = explode(', ', parent::getColors());
      $colors = array_filter($colors);
    }

    return $colors;
  }

}
