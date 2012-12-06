<?php

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
