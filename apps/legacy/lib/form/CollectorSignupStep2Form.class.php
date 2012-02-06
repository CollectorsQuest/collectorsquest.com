<?php

class CollectorSignupStep2Form extends BaseFormPropel
{
  public function setup()
  {
    $amCollectorType = array('' =>  '--- Select Collector Type ---') + CollectorProfilePeer::$collector_types;
    $this->setWidgets(array(
      'collector_type'      => new sfWidgetFormSelect(array('choices' => $amCollectorType), array("style" => "height: 30px;")),
      'what_you_collect'    => new sfWidgetFormInputText(),
      'purchase_per_year'   => new sfWidgetFormInputText(),
      'most_expensive_item' => new sfWidgetFormInputText(),
      'annually_spend'      => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'collector_type'      => new sfValidatorChoice(array('choices' => array_keys($amCollectorType), 'required' => true)),
      'what_you_collect'    => new sfValidatorString(array('max_length' => 50, 'required' => true)),
      'purchase_per_year'   => new sfValidatorNumber(array('required' => true)),
      'most_expensive_item' => new sfValidatorNumber(array('required' => false)),
      'annually_spend'      => new sfValidatorNumber(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('collectorstep2[%s]');

    $this->validatorSchema->setOption('allow_extra_fields', true);
    $this->validatorSchema->setOption('filter_extra_fields', true);

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Collector';
  }
}
