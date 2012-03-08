<?php

/**
 *
 */
class shippingRateAmountInCentsOrPercentValidatorSchema extends sfValidatorSchema
{

  protected function configure($options = array(), $messages = array())
  {
    $this->addMessage('missing_amount',
      'You must set either amount in cents or amount in percent!');
    $this->addMessage('both_amounts',
      'You have set both cent and percent amounts. Only one of the two must be set!');
  }

  protected function doClean($values)
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

    return $values;
  }

}
