<?php

/**
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
  }

  protected function doClean($values)
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

    return $values;
  }

}
