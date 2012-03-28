<?php

class ShoppingCartCollectibleCheckoutForm extends ShoppingCartCollectibleForm
{
  private $_salt_base = 'JpuD7HrhgYNMeem2nvsxLeddRMhWVJtP';

  public function setup()
  {
    parent::setup();

    $_salt = $this->getObject()->getCollectibleId() .'-'. $this->_salt_base;

    $this->setWidgets(array(
      'shopping_cart_id'  => new sfWidgetFormInputHidden(),
      'collectible_id'    => new sfWidgetFormInputHidden(),
      'country_iso3166'   => new cqWidgetFormI18nChoiceCountry(array('add_empty' => false, 'culture' => 'en')),
      'note_to_seller'    => new sfWidgetFormTextarea(),
      '_nonce_token'      => new IceWidgetNonceToken(array('action' => 'checkout', 'salt' => $_salt))
    ));

    $this->setValidators(array(
      'shopping_cart_id'  => new sfValidatorPropelChoice(array('model' => 'ShoppingCart', 'column' => 'id', 'required' => true)),
      'collectible_id'    => new sfValidatorPropelChoice(array('model' => 'Collectible', 'column' => 'id', 'required' => true)),
      'country_iso3166'   => new sfValidatorI18nChoiceCountry(array('required' => true)),
      'note_to_seller'    => new sfValidatorString(array('required' => false)),
      '_nonce_token'      => new IceValidatorNonceToken(array('action' => 'checkout', 'salt' => $_salt))
    ));

    // Default to United States
    $this->setDefault('country_iso3166', $this->getObject()->getShippingCountryIso3166());

    $this->widgetSchema->setNameFormat('checkout[%s]');
    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
  }

}
