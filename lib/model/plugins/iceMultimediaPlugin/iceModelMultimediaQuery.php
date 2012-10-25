<?php

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
class iceModelMultimediaQuery extends PluginiceModelMultimediaQuery
{

  /**
   * Filter the multimedia items based on a predefined color range
   *
   * @param     string $range
   * @return    MultimediaQuery
   *
   * @throws    Exception
   */
  public function filterByColorRange($range)
  {
    // first check if we have defined colors for this range
    if (!in_array($range, array_keys(MultimediaPeer::$color_table)))
    {
      throw new Exception(sprintf(
        'Undefined color range %s in cqMultimediaColorPickerQuery::filterByColorRange()',
        $range
      ));
    }

    // then build the query conditions
    $condition_names = array();
    foreach (MultimediaPeer::$color_table[$range] as $i => $color)
    {
      $condition_name = $range.'_'.$i;
      $condition_names[] = $condition_name;

      $this->condition($condition_name, 'Multimedia.Colors LIKE ?', '%'.$color.'%');
    }

    // and execute them
    return $this->where($condition_names, Criteria::LOGICAL_OR);
  }


}
