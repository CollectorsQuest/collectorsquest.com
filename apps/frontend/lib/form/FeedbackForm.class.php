<?php

class FeedbackForm extends BaseForm
{
  public function setup()
  {
    /**
     * Widgets
     */
    if(sfContext::getInstance()->getUser()->isAuthenticated())
    {
      $widget = new sfWidgetFormInputHidden();
    }
    else
    {
      $widget = new sfWidgetFormInputText();
    }
     
    $this->setWidgets(array(
      'fullname'    => $widget,
      'email'       => $widget,
      'message'     => new sfWidgetFormTextarea(),
      'page'        => new sfWidgetFormInputHidden(),

      'f_ip_address'          => new sfWidgetFormInputHidden(),
      'f_javascript_enabled'  => new sfWidgetFormInputHidden(),
      'f_browser_type'        => new sfWidgetFormInputHidden(),
      'f_browser_color_depth' => new sfWidgetFormInputHidden(),
      'f_resolution'          => new sfWidgetFormInputHidden(),
      'f_browser_size'        => new sfWidgetFormInputHidden(),

      'send_copy' => new sfWidgetFormInputCheckbox()
    ));

    $this->setValidators(array(
      'fullname'    => new sfValidatorString(array('max_length' => 64, 'required' => true)),
      'email'       => new sfValidatorEmail(array('required' => false)),
      'message'     => new sfValidatorString(array('min_length' => 5, 'required' => true)),
      'page'        => new sfValidatorString(array('required' => true)),

      'f_ip_address'           => new sfValidatorPass(),
      'f_javascript_enabled'   => new sfValidatorPass(),
      'f_browser_type'         => new sfValidatorPass(),
      'f_browser_version'      => new sfValidatorPass(),
      'f_browser_color_depth'  => new sfValidatorPass(),
      'f_resolution'           => new sfValidatorPass(),
      'f_browser_size'         => new sfValidatorPass(),

      'send_copy' => new sfValidatorString(array('required' => false))
    ));

    $this->widgetSchema->setNameFormat('feedback[%s]');
    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }
}
