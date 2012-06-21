<?php

class SimpleShippingCollectorCollectibleForCountryFormValidatorSchema extends sfValidatorSchema
{

  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->addMessage('flat_rate_missing', 'Please enter a valid shipping amount.');
  }

  protected function doClean($values)
  {
    if (
      ShippingReferencePeer::SHIPPING_TYPE_FLAT_RATE == $values['shipping_type'] &&
      intval($values['flat_rate']) < 1
    ) {
        $errorSchema = new sfValidatorErrorSchema($this);
        $error = new sfValidatorError($this, 'flat_rate_missing');

        $errorSchema->addError(new sfValidatorError($this, 'flat_rate_missing'),
          'flat_rate');

        throw $errorSchema;
    }

    return $values;
  }

}
