<?php

class FeedbackForm extends BaseForm
{
  public function setup()
  {
    /**
     * Widgets
     */
    $this->setWidgets(array(
      'fullname'    => new sfWidgetFormInputText(),
      'email'       => new sfWidgetFormInputText(),
      'message'     => new sfWidgetFormTextarea(),
      'page'        => new sfWidgetFormInputHidden(),

      'f_ip_address'          => new sfWidgetFormInputHidden(),
      'f_javascript_enabled'  => new sfWidgetFormInputHidden(),
      'f_browser_type'        => new sfWidgetFormInputHidden(),
      'f_browser_color_depth' => new sfWidgetFormInputHidden(),
      'f_resolution'          => new sfWidgetFormInputHidden(),
      'f_browser_size'        => new sfWidgetFormInputHidden()
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
      'f_browser_size'         => new sfValidatorPass()
    ));

    $this->widgetSchema->setNameFormat('feedback[%s]');
    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }
}
