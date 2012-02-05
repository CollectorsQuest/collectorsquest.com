<?php

class CollectorProfileEditForm extends BaseCollectorProfileForm
{
  public function configure()
  {
    $years = array_combine(range(date('Y') - 100, date('Y')), range(date('Y') - 100, date('Y')));

    $this->setWidgets(array(
      'id' => new sfWidgetFormInputHidden(),
      'birthday' => new sfWidgetFormDate(array('years' => $years)),
      'gender' => new sfWidgetFormSelect(array('choices' => array('' => null, 'f' => 'Female', 'm' => 'Male'))),
      'zip_postal' => new sfWidgetFormInputText(),
      'country' => new sfWidgetFormI18nSelectCountry(array('add_empty' => true, 'culture' => 'en')),
      'website' => new sfWidgetFormInputText(),
      'about' => new sfWidgetFormTextarea(),
      'collections' => new sfWidgetFormTextarea(),
      'collecting' => new sfWidgetFormInputText(),
      'most_spent' => new sfWidgetFormInputText(),
      'anually_spent' => new sfWidgetFormInputText(),
      'new_item_every' => new sfWidgetFormInputText(),
      'interests' => new sfWidgetFormTextarea(),
      'notifications' => new sfWidgetFormTextarea(),
      'collector_type' => new sfWidgetFormChoice(array('choices' => $this->getCollectorTypeChoices(), 'expanded' => true)),
    ));

    $this->setValidators(array(
      'id' => new sfValidatorPropelChoice(array('model' => 'CollectorProfile', 'column' => 'id', 'required' => true)),
      'birthday' => new sfValidatorDate(array('required' => false)),
      'gender' => new sfValidatorChoice(array('choices' => array('f', 'm'), 'required' => false)),
      'zip_postal' => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'country' => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'website' => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'about' => new sfValidatorString(array('required' => false)),
      'collections' => new sfValidatorString(array('required' => false)),
      'collecting' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'most_spent' => new sfValidatorNumber(array('min' => 0, 'max' => 2147483647, 'required' => false)),
      'anually_spent' => new sfValidatorNumber(array('min' => 0, 'max' => 2147483647, 'required' => false)),
      'new_item_every' => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'interests' => new sfValidatorString(array('required' => false)),
      'notifications' => new sfValidatorString(array('required' => false)),
      'collector_type' => new sfValidatorChoice(array('choices' => array_keys($this->getCollectorTypeChoices()), 'required' => true)),
    ));
  }

  public function getCollectorTypeChoices()
  {
    return array(
      'casual' => 'Casual',
      'occasional' => 'Occasional',
      'serious' => 'Serious',
      'obsessive' => 'Obsessive',
      'expert' => 'Expert',
    );
  }

}
