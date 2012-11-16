<?php

/**
 * Filename: CollectorCreatePasswordForm.class.php
 *
 * Form to create username, password for rpx created accounts
 *
 * @author Yanko Simeonoff <ysimeonoff@collectorsquest.com>
 * @since 10/31/12
 * Id: $Id$
 */

class CollectorCreatePasswordForm extends CollectorForm
{

  public function configure()
  {
    parent::configure();

    $this->useFields(array(
      'username',
      'email',
    ));

    $this->widgetSchema['password'] = new sfWidgetFormInputPassword(array(
        'label'       => 'New Password',
      ), array(
        'placeholder' => 'Enter your new password',
        'required'    => 'required',
    ));
    $this->widgetSchema['password_again'] = new sfWidgetFormInputPassword(array(
        'label'       => 'Confirm New Password',
      ), array(
        'placeholder' => 'Confirm your new password',
        'required'    => 'required',
    ));

    $this->validatorSchema['password'] = new sfValidatorString(
      array(
        'min_length' => 6,
        'max_length' => 50,
        'required'   => false,
      ), array(
      'max_length' => 'The password is too long (%max_length% characters max).',
      'min_length' => 'The password is too short (%min_length% characters min).',
    ));
    $this->validatorSchema['password_again'] = new sfValidatorPass();

    $this->mergePostValidator(new sfValidatorAnd(array(
      new sfValidatorSchemaCompare(
        'password', sfValidatorSchemaCompare::EQUAL, 'password_again',
        array('throw_global_error' => true),
        array('invalid' => 'The two passwords do not match, please enter them again!')),
    )));

    $this->widgetSchema->setFormFormatterName('Bootstrap');
  }
}
