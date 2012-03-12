<?php

/**
 * The shipping rate form requires either a shipping amount in cents or in
 * percent is set. Make sure it is :)
 */
class shippingRateAmountInCentsOrPercentValidatorSchema extends sfValidatorSchema
{

  protected function configure($options = array(), $messages = array())
  {
    $this->addMessage('missing_amount',
      'You must set either amount in cents or amount in percent!');
    $this->addMessage('both_amounts',
      'You have set both cent and percent amounts. Only one of the two must be set!');
    $this->addMessage('no_amount_required',
      'You should not set an amount with your currently selected calculation type');
  }

  protected function doClean($values)
  {
    // if calculation_type requires an amount to be set
    if (in_array($values['calculation_type'], ShippingRatePeer::$calculation_type_amount['required']))
    {
      // if neither amount in cents nor in percent is set throw an error
      if ( 0 == $values['amount_in_cents'] && 0 == $values['amount_in_percent'])
      {
        $errorSchema = new sfValidatorErrorSchema($this);
        $error = new sfValidatorError($this, 'missing_amount');

        // add the error to both fields
        $errorSchema->addError($error, 'amount_in_cents');
        $errorSchema->addError($error, 'amount_in_percent');

        throw $errorSchema;
      }

      // if both amount in cents and in percent is set throw an error
      if ( 0 != $values['amount_in_cents'] && 0 != $values['amount_in_percent'])
      {
        $errorSchema = new sfValidatorErrorSchema($this);
        $error = new sfValidatorError($this, 'both_amounts');

        // add the error to both fields
        $errorSchema->addError($error, 'amount_in_cents');
        $errorSchema->addError($error, 'amount_in_percent');

        throw $errorSchema;
      }
    }
    // else if calculation_type does not require an amount set
    if (in_array($values['calculation_type'], ShippingRatePeer::$calculation_type_amount['not-required']))
    {
      // sometimes the amount fields will not be available;
      // in this case there is no reason to check it they have values
      if (!(isset($values['amount_in_cents']) && isset($values['amount_in_percent'])))
      {
        return $values;
      }

      // if both amount in cents and in percent is set throw an error
      if ( 0 != $values['amount_in_cents'] || 0 != $values['amount_in_percent'])
      {
        $errorSchema = new sfValidatorErrorSchema($this);
        $error = new sfValidatorError($this, 'no_amount_required');

        // add the error to both fields
        $errorSchema->addError($error, 'amount_in_cents');
        $errorSchema->addError($error, 'amount_in_percent');

        throw $errorSchema;
      }
    }

    return $values;
  }

}
