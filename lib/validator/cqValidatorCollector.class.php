<?php

/**
 * Validates a collector on login.
 */
class cqValidatorSchemaCollector extends sfValidatorSchema
{
  /**
   * Available options:
   *
   *  * username_field      Field name of username field (username by default)
   *  * password_field      Field name of password field (password by default)
   *  * throw_global_error  Throws a global error if true (false by default)
   *
   * @see sfValidatorBase
   */
  public function configure($options = array(), $messages = array())
  {
    $this->addOption('username_field', 'username');
    $this->addOption('password_field', 'password');
    $this->addOption('check_password_callback',
      array(__CLASS__, 'defaultCheckPassword'));
    $this->addOption('throw_global_error', false);


    $this->setMessage('invalid', 'The username and/or password is invalid.');
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($values)
  {
    // only validate if username and password are both present
    if (isset($values[$this->getOption('username_field')]) && isset($values[$this->getOption('password_field')]))
    {
      $username = $values[$this->getOption('username_field')];
      $password = $values[$this->getOption('password_field')];

      // collector exists?
      if (( $collector = CollectorPeer::retrieveByUsername($username) ))
      {
        // password is ok?
        if ($collector->checkPassword($password))
        {
          return array_merge($values, array('collector' => $collector));
        }
      }

      if ($this->getOption('throw_global_error'))
      {
        throw new sfValidatorError($this, 'invalid');
      }

      throw new sfValidatorErrorSchema($this, array(
        $this->getOption('username_field') => new sfValidatorError($this, 'invalid'),
      ));
    }

    // assume a required error has already been thrown, skip validation
    return $values;
  }


}
