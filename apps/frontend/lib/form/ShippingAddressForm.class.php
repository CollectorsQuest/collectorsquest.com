<?php

class ShippingAddressForm extends CollectorAddressForm
{
  public function setup()
  {
    $this->setWidgets(array(
      'full_name'       => new sfWidgetFormInputText(
        array(), array('required' => 'required')
      ),
      'address_line_1'  => new sfWidgetFormInputText(
        array(), array('required' => 'required')
      ),
      'address_line_2'  => new sfWidgetFormInputText(),
      'city'            => new sfWidgetFormInputText(
        array(), array('required' => 'required')
      ),
      'state_region'    => new sfWidgetFormInputText(
        array(), array('required' => 'required')
      ),
      'zip_postcode'    => new sfWidgetFormInputText(
        array(), array('required' => 'required')
      ),
      'country_iso3166' => new cqWidgetFormI18nChoiceIceModelGeoCountry(
        array('add_empty' => false), array('required' => 'required')
      ),

      'address_id' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'full_name'       => new sfValidatorString(array('max_length' => 255)),
      'address_line_1'  => new sfValidatorString(array('max_length' => 255)),
      'address_line_2'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'city'            => new sfValidatorString(array('max_length' => 100)),
      'state_region'    => new sfValidatorString(array('max_length' => 100, 'required' => true)),
      'zip_postcode'    => new sfValidatorString(array('max_length' => 50, 'required' => true)),
      'country_iso3166' => new sfValidatorPropelChoice(
        array('model' => 'iceModelGeoCountry', 'column' => 'iso3166')
      ),

      'address_id' => new sfValidatorPropelChoice(
        array('model' => 'CollectorAddress', 'column' => 'id', 'required' => false)
      ),
    ));

    $this->widgetSchema->setNameFormat('shipping_address[%s]');
    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
  }

}
