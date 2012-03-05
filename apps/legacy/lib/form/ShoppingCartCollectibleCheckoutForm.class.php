<?php

class ShoppingCartCollectibleCheckoutForm extends sfForm
{
  private $_salt_base = 'JpuD7HrhgYNMeem2nvsxLeddRMhWVJtP';

  public function setup()
  {
    $_salt = $this->getDefault('collectible_for_sale_id') .'-'. $this->_salt_base;

    $this->setWidgets(array(
      'shopping_cart_id'          => new sfWidgetFormInputHidden(),
      'collectible_for_sale_id'   => new sfWidgetFormInputHidden(),
      'note_to_seller'            => new sfWidgetFormTextarea(),
      '_nonce_token'              => new IceWidgetNonceToken(array('action' => 'checkout', 'salt' => $_salt))
    ));

    $this->setValidators(array(
      'shopping_cart_id'         => new sfValidatorPropelChoice(array('model' => 'ShoppingCart', 'column' => 'id', 'required' => true)),
      'collectible_for_sale_id'  => new sfValidatorPropelChoice(array('model' => 'CollectibleForSale', 'column' => 'id', 'required' => true)),
      'note_to_seller'           => new sfValidatorString(array('required' => false)),
      '_nonce_token'             => new IceValidatorNonceToken(array('action' => 'checkout', 'salt' => $_salt))
    ));

    $this->widgetSchema->setNameFormat('checkout[%s]');
    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

}
