<?php

class SimpleShippingCollectorCollectibleForCountryFormValidatorSchema extends sfValidatorSchema
{

  /**
   * Remove $fields param from the constructor as it is not used
   *
   * @param     array $options
   * @param     array $messages
   */
  public function __construct($options = array(), $messages = array())
  {
    parent::__construct(null, $options, $messages);
  }

  /**
   * Messages:
   *  - flat_rate_missing
   *  - combined_flat_rate_higher_than_flat_rate
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->addMessage('flat_rate_missing', 'Please enter a valid shipping amount.');
    $this->addMessage('combined_flat_rate_higher_than_flat_rate', 'Shipping rate with another item must be lower or equal to the rate of shipping a single item.');
  }

  /**
   * @param     array $values
   * @return    array
   *
   * @throws sfValidatorErrorSchema
   */
  protected function doClean($values)
  {
    $errorSchema = new sfValidatorErrorSchema($this);


    if (ShippingReferencePeer::SHIPPING_TYPE_FLAT_RATE == $values['shipping_type'])
    {
      // if flat rate is under the allowed value
      if ($values['flat_rate'] < 0.01)
      {
        $errorSchema->addError(
          new sfValidatorError($this, 'flat_rate_missing'),
          'flat_rate'
        );
      }
      // if combined flat rate is higher than standard flat rate
      elseif (1 === bccomp($values['combined_flat_rate'], $values['flat_rate'], 2))
      {
        $errorSchema->addError(
          new sfValidatorError($this, 'combined_flat_rate_higher_than_flat_rate'),
          'combined_flat_rate'
        );
      }
      elseif ($values['combined_flat_rate'] <= 0)
      {
        $values['combined_flat_rate'] = $values['flat_rate'];
      }
    }

    // if we have added erros, throw the error schema
    if (count($errorSchema))
    {
      throw $errorSchema;
    }

    return $values;
  }

}
