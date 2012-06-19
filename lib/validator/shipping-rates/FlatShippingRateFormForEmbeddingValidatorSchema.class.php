<?php

class FlatShippingRateFormForEmbeddingValidatorSchema extends sfValidatorSchema
{

  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->addMessage('flat_rate_or_free',
      'You must either enter a cost of shipping or free shipping');
  }

  protected function doClean($values)
  {
    if ($values['shipping_carrier_service_id'])
    {
      if (0 == $values['flat_rate_in_cents'] && !$values['is_free_shipping'])
      {
        $errorSchema = new sfValidatorErrorSchema($this);
        $error = new sfValidatorError($this, 'flat_rate_or_free');

        // add the error to both fields
        $errorSchema->addError($error, 'flat_rate_in_cents');
        $errorSchema->addError($error, 'is_free_shipping');

        throw $errorSchema;
      }
    }

    // if free shipping is set flat rate is 0
    if ($values['is_free_shipping'])
    {
      $values['flat_rate_in_cents'] = 0;
    }

    return $values;
  }

}