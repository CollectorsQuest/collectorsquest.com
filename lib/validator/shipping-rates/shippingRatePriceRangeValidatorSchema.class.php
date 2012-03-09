<?php

/**
 * When price range is set, it must be valid. This means min < max unless max = 0
 *
 */
class shippingRatePriceRangeValidatorSchema extends sfValidatorSchema
{

  protected function configure($options = array(), $messages = array())
  {
    $this->addMessage('max_higher_than_min',
      'The "price range max" value must be higher than the min value.
       If you want to specify a range of %price_range_min% to infinity, enter
       0 for "price range max"');

    $this->addMessage('price_range_not_required',
      'Price range is not requiret when calculation type is not price range');
  }

  protected function doClean($values)
  {
    // if calculation type is price range
    if (ShippingRatePeer::CALCULATION_TYPE_PRICE_RANGE == $values['calculation_type'])
    {
      // if the value for range max is smaller than that of range min
      // and it is not set to 0 (which we interpret as infinity)
      if ( ($values['price_range_max'] < $values['price_range_min'])
        && 0 != $values['price_range_max'] )
      {
        // throw an error about wrong price range
        $errorSchema = new sfValidatorErrorSchema($this);
        $errorSchema->addError(
          new sfValidatorError($this, 'max_higher_than_min', array(
              'price_range_min' => $values['price_range_min'],
              'price_range_max' => $values['price_range_max'],
          )),
          'price_range_max' // the field that the error is added to
        );

        throw $errorSchema;
      }
    }
    // if calculation type is not price range
    else
    {
      // and one of the price range extremes is set
      if (0 != $values['price_range_max'] || 0 != $values['price_range_min'])
      {
        // throw an error about price range not required
        $errorSchema = new sfValidatorErrorSchema($this);
        $error = new sfValidatorError($this, 'price_range_not_required');

        // add the error to both fields
        $errorSchema->addError($error, 'price_range_min');
        $errorSchema->addError($error, 'price_range_max');

        throw $errorSchema;
      }
    }

    return $values;
  }

}
