<?php

class CollectorSignupStep1Form extends BaseForm
{
  public function configure()
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
      'email'           => new sfWidgetFormInputText(array(), array(
          'type'        => 'email',
          'required'    => 'required',
          'placeholder' => 'Email'
      )),
      'seller'          => new sfWidgetFormSelectRadio(array(
        'label'       => 'Choose one',
        'choices'     => array(
          0 => 'I collect',
          1 => 'I sell',
          2 => 'I collect and sell',
        ),

        'formatter'   =>   function($widget, $inputs) {
          $rows = array();
          foreach ($inputs as $input)
          {
            $rows[] = $widget->renderContentTag('label',
              $input['input'].$widget->getOption('label_separator').strip_tags($input['label']),
              array('class'=>'radio'));
          }

          return !$rows ? '' : $widget->renderContentTag('div', implode($widget->getOption('separator'), $rows), array('class' => $widget->getOption('class')));
        }

      ), array(
        'required' => 'required',
        'style' => 'padding-top: 3px;'
      )),
      'newsletter'  => new sfWidgetFormInputCheckbox(array(), array(
        'checked'   => 'checked'
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
          'invalid'    => 'Only letters, numbers, periods and underscores allowed. Must start with a letter.',
          'max_length' => 'The username "%value%" is too long (%max_length% characters max).',
          'min_length' => 'The username "%value%" is too short (%min_length% characters min).',
        )
      ),
      'password'       => new sfValidatorString(
        array(
          'min_length' => 6,
          'max_length' => 50,
          'required'   => true,
         ), array(
          'max_length' => 'The password is too long (%max_length% characters max).',
          'min_length' => 'The password is too short (%min_length% characters min).',
       )),
      'seller'         => new sfValidatorChoice(array(
          'choices'    => array(0,1,2),
          'required'   => true,
      )),
      'email'          => new sfValidatorEmail(
        array(
          'required'   => true
        ), array(
          'invalid'    => 'This email address is invalid.',
        )),
      'newsletter'     => new sfValidatorBoolean(array('required' => false))
    ));

    $this->validatorSchema['password_again'] = clone $this->validatorSchema['password'];

    $this->mergePostValidator(new sfValidatorPropelUnique(
      array('model' => 'Collector', 'column' => array('username')),
      array('invalid' => 'This username is already taken, please choose another one!')
    ));

    if (isset($_POST['signup_step1']['email']) && $_POST['signup_step1']['email'] != '')
    {
	    $this->mergePostValidator(new sfValidatorPropelUnique(
	      array('model' => 'Collector', 'column' => array('email')),
	      array('invalid' => 'This email already has an account, did you forget your password?')
	    ));
    }

    $this->mergePostValidator(new sfValidatorSchemaCompare(
      'password', sfValidatorSchemaCompare::EQUAL, 'password_again',
      array('throw_global_error' => true),
      array('invalid' => 'The two passwords do not match, please enter them again!')
    ));

    $this->setDefault('seller', 0);

    $this->widgetSchema->setNameFormat('signup_step1[%s]');
    $this->widgetSchema->setFormFormatterName('Bootstrap');
  }

  public function getJavaScripts()
  {
    return array(
        'jquery/mailcheck.js',
    );
  }

}
