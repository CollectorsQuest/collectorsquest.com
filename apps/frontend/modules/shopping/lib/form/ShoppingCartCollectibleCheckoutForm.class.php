<?php

class ShoppingCartCollectibleCheckoutForm extends ShoppingCartCollectibleForm
{
  /* @var string */
  private $_salt_base = 'JpuD7HrhgYNMeem2nvsxLeddRMhWVJtP';

  public function setup()
  {
    parent::setup();

    $_salt = $this->getObject()->getCollectibleId() .'-'. $this->_salt_base;

    $this->setWidgets(array(
      'shopping_cart_id'  => new sfWidgetFormInputHidden(),
      'collectible_id'    => new sfWidgetFormInputHidden(),
      'country_iso3166'   => new cqWidgetFormI18nChoiceIceModelGeoCountry(array('add_empty' => false, 'culture' => 'en')),
      'note_to_seller'    => new sfWidgetFormTextarea(),
      'promotion_code'    => new sfWidgetFormInputText(),
      '_nonce_token'      => new IceWidgetNonceToken(array('action' => 'checkout', 'salt' => $_salt))
    ));

    $this->setValidators(array(
      'shopping_cart_id'  => new sfValidatorPropelChoice(array('model' => 'ShoppingCart', 'column' => 'id', 'required' => true)),
      'collectible_id'    => new sfValidatorPropelChoice(array('model' => 'Collectible', 'column' => 'id', 'required' => true)),
      'country_iso3166'   => new sfValidatorPropelChoice(
        array('model' => 'iceModelGeoCountry', 'column' => 'iso3166',)
      ),
      'note_to_seller'    => new sfValidatorString(array('required' => false)),
      'promotion_code'    => new sfValidatorString(array('required' => false)),
      '_nonce_token'      => new IceValidatorNonceToken(array('action' => 'checkout', 'salt' => $_salt))
    ));

    // Default to United States
    $this->setDefault('country_iso3166', $this->getObject()->getShippingCountryIso3166());

    if ($promotion = $this->getObject()->getSellerPromotion())
    {
      $this->setDefault('promotion_code', $promotion->getPromotionCode());
    }

    $this->setupStateField();

    $this->widgetSchema->setNameFormat('checkout[%s]');
    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
  }

  private function setupStateField()
  {
    if ($this->getObject()->getCollectible())
    {
      /* @var $collectible_for_sale CollectibleForSale */
      $collectible_for_sale = $this->getObject()->getCollectibleForSale();

      // Show state field only if
      // collectible with tax and country is same and tax_state definite
      if (
        !$collectible_for_sale->getTaxPercentage()
        || $collectible_for_sale->getTaxCountry() != $this->getObject()->getShippingCountryIso3166()
        || !$collectible_for_sale->getTaxState()
      )
      {
        return;
      }

      if ($states = $this->getStatesForCountry($this->getObject()->getShippingCountryIso3166()))
      {
        if (!$this->getObject()->getShippingStateRegion())
        {
          $this->getObject()->setShippingStateRegion(0)->updateTaxAmount();
        }
        $this->widgetSchema['state_region'] =  new sfWidgetFormChoice(
          array('choices' => array(0 => '--- select your state ---') + $states), array('style' => 'width: 100%;')
        );
        $this->validatorSchema['state_region'] = new sfValidatorChoice(
          array('choices' => array_keys($states), 'required' => true)
        );
      }
      else
      {
        $this->widgetSchema['state_region'] =  new sfWidgetFormInputText(
          array(), array('style' => 'width: 91%;')
        );
        $this->validatorSchema['state_region'] = new sfValidatorString(
          array('max_length' => 100, 'required' => false)
        );
      }

      $this->setDefault('state_region', $this->getObject()->getShippingStateRegion());
    }
    else
    {
      $this->widgetSchema['state_region'] =  new sfWidgetFormInputHidden();
      $this->validatorSchema['state_region'] = new sfValidatorString(
        array('max_length' => 100, 'required' => false)
      );
    }
  }

  private function getStatesForCountry($country_iso3166)
  {
    $states = iceModelGeoRegionQuery::create()
      ->orderByNameLatin()
      ->useiceModelGeoCountryQuery()
        ->filterByIso3166($country_iso3166)
      ->endUse()
      ->select(array('Id', 'NameLatin'))
      ->find()
      ->toKeyValue('Id', 'NameLatin');

    return $states;
  }

}
