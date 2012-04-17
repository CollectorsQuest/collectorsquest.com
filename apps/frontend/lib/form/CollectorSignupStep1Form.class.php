<?php

class CollectorSignupStep1Form extends BaseForm
{
  public function setup()
  {
    $this->setWidgets(array(
      'username'        => new sfWidgetFormInputText(array(
          'label'       => 'Username'
        ), array(
          'pattern'     => '^(?=^[a-zA-Z])[a-zA-Z0-9\.\_]*$',
          'required'    => 'required',
          'placeholder' => 'Username',
      )),
      'password'        => new sfWidgetFormInputPassword(array(), array(
          'required'    => 'required',
          'placeholder' => 'Password',
      )),
      'password_again'  => new sfWidgetFormInputPassword(array(
          'label'       => 'Confirm Password'
        ), array(
          'required'    => 'required',
          'placeholder' => 'Confirm Password'
      )),
      'display_name'    => new sfWidgetFormInputText(array(), array(
          'placeholder' => 'Display Name'
      )),
      'email'           => new sfWidgetFormInputText(array(), array(
          'type'        => 'email',
          'required'    => 'required',
          'placeholder' => 'Email'
      )),
    ));

    $this->setValidators(array(
      'username'       => new sfValidatorRegex(
        array(
          'pattern'    => '/^(?=^[a-zA-Z])[a-zA-Z0-9\.\_]*$/',
          'min_length' => 3,
          'max_length' => 50,
          'required'   => true,
        ),
        array(
          'invalid' => 'You have disallowed symbols in your username',
        )
      ),
      'password'       => new sfValidatorString(array(
          'min_length' => 6,
          'max_length' => 50,
          'required'   => true,
      )),
      'password_again' => new sfValidatorPass(),
      'display_name'   => new sfValidatorString(array(
          'max_length' => 50,
          'required'   => false
      )),
      'email'          => new sfValidatorEmail(array(
          'required'   => true
      )),
    ));

    $this->mergePostValidator(new sfValidatorPropelUnique(
      array('model' => 'Collector', 'column' => array('username')),
      array('invalid' => 'This username is already taken, please choose another one!')
    ));

    $this->mergePostValidator(new sfValidatorPropelUnique(
      array('model' => 'Collector', 'column' => array('email')),
      array('invalid' => 'This email already has an account, did you forget your password?')
    ));

    $this->mergePostValidator(new sfValidatorSchemaCompare(
      'password', sfValidatorSchemaCompare::EQUAL, 'password_again',
      array('throw_global_error' => true),
      array('invalid' => 'The two passwords do not match, please enter them again!')
    ));

    $this->widgetSchema->setNameFormat('signup_step1[%s]');
    $this->widgetSchema->setFormFormatterName('Bootstrap');
  }

  public function getJavaScripts()
  {
    return array(
        '/js/jquery/mailcheck.js',
    );
  }

}
