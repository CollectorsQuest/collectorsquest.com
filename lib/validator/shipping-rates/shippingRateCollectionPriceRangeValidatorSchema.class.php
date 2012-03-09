<?php

/**
 * shippingRateCollectionPriceRangeValidator validates multiple embedded
 * shippingRate forms if their combined range is valid
 */
class shippingRateCollectionPriceRangeValidatorSchema extends sfValidatorSchema
{

  protected function configure($options = array(), $messages = array())
  {
    $this->addRequiredOption('embedded_form_names');

    $this->addMessage('must_start_from_0',
      'You must select a range starting from 0');
    $this->addMessage('intersecting_ranges',
      'You have ranges that intersect around %point1%, %point2%');
    $this->addMessage('hole_in_range',
      'You have a hole in your ranges around %point1%, %point2%');
    $this->addMessage('must_end_with_0',
      'Your ending range must be with a max of 0, which means infinity');
  }

  protected function doClean($values)
  {
    if (ShippingRatePeer::CALCULATION_TYPE_PRICE_RANGE
        !=
        $values['calculation_type'])
    {
      return $values;
    }

    // prepare an error schema object
    $errorSchema = new sfValidatorErrorSchema($this);

    // create an array of values to validate, based on the embedded forms option
    $to_validate = array();
    foreach ($this->getOption('embedded_form_names') as $embedded_form)
    {
      $to_validate[$embedded_form] = $values[$embedded_form];
    }

    // sort the values to validate based on price_range_min, from lowest to highest
    uasort($to_validate, function($a, $b)
    {
      if ($a['price_range_min'] == $b['price_range_min'])
      {
        return 0;
      }

      return $a['price_range_min'] < $b['price_range_min']
        ? -1
        : 1;
    });

    // start with the first object in preparation for while loop
    $current = array_shift($to_validate);

    // check if the first value array starts from min 0
    if (0 != $current['price_range_min'])
    {
      $errorSchema->addError(new sfValidatorError($this, 'must_start_from_0'));
    }

    // prepare variables for first iteration
    $last_min = $current['price_range_min'];
    $last_max = $current['price_range_max'];

    // loop over embedded forms and validate for intersections or holes
    while ($current = array_shift($to_validate))
    {
      // check for intersecting ranges
      if ( $last_max > $current['price_range_min'] )
      {
        $errorSchema->addError(new sfValidatorError(
          $this, 'intersecting_ranges', array(
            'point1' => $last_max,
            'point2' => $current['price_range_min'],
        )));
      }

      // check for holes in the ranges, accounting for ranges of type:
      // array(min => 0, max => 10), array(min => 11, max => whatever)
      if ($last_max  < ($current['price_range_min'] - 1) )
      {
        $errorSchema->addError(new sfValidatorError(
          $this, 'hole_in_range', array(
            'point1' => $last_max,
            'point2' => $current['price_range_min'],
        )));
      }

      // prepare for next iteration
      $last_min = $current['price_range_min'];
      $last_max = $current['price_range_max'];
    }

    // check that last form has a max range of 0 (infinity)
    if (0 != $last_max)
    {
      $errorSchema->addError(new sfValidatorError($this, 'must_end_with_0'));
    }

    // if errors were found, throw them at the user
    if (count($errorSchema))
    {
      throw $errorSchema;
    }

    return $values;
  }

}
