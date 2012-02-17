<?php

class CollectorSignupStep1Form extends BaseForm
{
  public function setup()
  {
    $this->setWidgets(array(
      'username'        => new sfWidgetFormInputText(array(), array(
          'pattern'     => '(?=^[a-zA-Z])[a-zA-Z0-9\.\_]*',
          'required'    => 'required',
      )),
      'password'        => new sfWidgetFormInputPassword(),
      'password_again'  => new sfWidgetFormInputPassword(),
      'display_name'    => new sfWidgetFormInputText(),
      'email'           => new sfWidgetFormInputText(array(), array(
          'type'        => 'email',
      )),
    ));

    $this->setValidators(array(
      'username'       => new sfValidatorRegex(array(
          'pattern'    => '/(?=^[a-zA-Z])[a-zA-Z0-9\.\_]*/',
          'min_length' => 3,
          'max_length' => 50,
          'required'   => true,
      )),
      'password'       => new sfValidatorString(array(
          'min_length' => 6,
          'max_length' => 50,
          'required'   => true,
      )),
      'password_again' => new sfValidatorPass(),
      'display_name'   => new sfValidatorString(array(
          'max_length' => 50,
          'required' => true
      )),
      'email'          => new sfValidatorEmail(array(
            'required' => true
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

    $this->widgetSchema->setNameFormat('collectorstep1[%s]');

    // $this->validatorSchema->setOption('allow_extra_fields', true);
    // $this->validatorSchema->setOption('filter_extra_fields', true);

    // $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
  }

}
