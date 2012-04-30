<?php

require 'lib/model/plugins/iceMultimediaPlugin/om/BaseMultimedia.php';

class Multimedia extends BaseMultimedia
{
  public function setColors($colors)
  {
    if (is_array($colors))
    {
      $colors = implode(', ', $colors);
    }

    return parent::setColors($colors);
  }

  public function getColors()
  {
    $colors = explode(', ', parent::getColors());
    $colors = array_filter($colors);

    return $colors;
  }
}
