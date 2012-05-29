<?php

class CollectorEditFormPasswordSchemaValidator extends sfValidatorSchema
{
  public function configure($options = array(), $messages = array())
  {
    $this->addRequiredOption('collector');
    $this->addOption('old_password_field', 'old_password');
    $this->addOption('new_password_field', 'password');

    $this->addMessage('invalid_old_password', "The password you've entered is wrong");
    $this->addMessage('missing_old_password', "You must enter your old password");
  }

  public function doClean($values)
  {
    $errorSchema = new sfValidatorErrorSchema($this);
    if ($values[$this->getOption('new_password_field')])
    {
      if (!$values[$this->getOption('old_password_field')])
      {
        $errorSchema->addError(new sfValidatorError($this, 'missing_old_password'),
          $this->getOption('old_password_field'));
      }
    }

    if ($values[$this->getOption('old_password_field')])
    {
      $collector = $this->getOption('collector');
      if (!$collector->checkPassword($values[$this->getOption('old_password_field')]))
      {
        $errorSchema->addError(new sfValidatorError($this, 'invalid_old_password'),
          $this->getOption('old_password_field'));
      }
    }

    if ($errorSchema->count())
    {
      throw $errorSchema;
    }

    return $values;
  }
}