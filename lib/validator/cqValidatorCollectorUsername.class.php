<?php

/**
 * Description of cqValidatorCollectorUsername
 */
class cqValidatorCollectorUsername extends sfValidatorString
{

  /**
   * Configures the current validator.
   *
   * @param array $options   An array of options
   * @param array $messages  An array of error messages
   *
   * @see   sfValidatorString
   */
  public function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
  }

  public function doClean($value)
  {
    $value = parent::doClean($value);

    // make sure we are logging in user without ' ' in username
    $value = preg_replace('/\s+/', '', $value);

    return $value;
  }

}

