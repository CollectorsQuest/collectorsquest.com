<?php

/**
 * AyahValidatorSchema
 *
 */
class AyahValidatorSchema extends sfValidatorSchema
{

  /**
   * Remove the $fields variable from the constructor
   *
   * @param array $options
   * @param array $messages
   */
  public function __construct($options = array(), $messages = array())
  {
    parent::__construct(null, $options, $messages);
  }

  /**
   * Available options:
   *
   *  - force_skip_check:  boolean/callable - force spam check to be skipped
   *
   * @param     array $options
   * @param     array $messages
   */
  public function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->addOption('force_skip_check', false);

    $this->addMessage('spam', 'The form failed the "Are You a Human" check');
    $this->addMessage('spam_field', 'This field failed "Are You a Human" check');
  }

  public function doClean($values)
  {
    // check for forcible skip spam check, eitehr directly or by callable
    $skip_check = $this->getOption('force_skip_check');
    if (true === $skip_check ||
        (is_callable($skip_check) && true === call_user_func($skip_check, $values))
    ) {
      // we are forced to skip the check by the form, just return the values
      return $values;
    }

    if (!cqStatic::getAyahClient()->scoreResult())
    {
      throw new sfValidatorError($this, 'spam');
    }

    return $values;
  }

}
