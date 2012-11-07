<?php

require 'lib/model/plugins/iceMultimediaPlugin/om/BaseiceModelMultimediaQuery.php';

class iceModelMultimediaQuery extends PluginiceModelMultimediaQuery
{

  /**
   * Filter the multimedia items based on a predefined color range
   *
   * @param     string $range
   * @return    iceModelMultimediaQuery
   *
   * @throws    Exception
   */
  public function filterByColorRange($range)
  {
    // first check if we have defined colors for this range
    if (!in_array($range, array_keys(iceModelMultimediaPeer::$color_table)))
    {
      throw new Exception(sprintf(
        'Undefined color range %s in cqMultimediaColorPickerQuery::filterByColorRange()',
        $range
      ));
    }

    // then build the query conditions
    $condition_names = array();
    foreach (iceModelMultimediaPeer::$color_table[$range] as $i => $color)
    {
      $condition_name = $range.'_'.$i;
      $condition_names[] = $condition_name;

      $this->condition($condition_name, 'Multimedia.Colors LIKE ?', '%'.$color.'%');
    }

    // and execute them
    return $this->where($condition_names, Criteria::LOGICAL_OR);
  }

}
