<?php

/**
 * ShoppingOrder form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Collectors
 */
class ShoppingOrderCheckoutForm extends ShoppingOrderForm
{
  private $_salt_base = 'JpuD7HrhgYNMeem2nvsxLeddRMhWVJtP';

  public function configure()
  {
    $_salt =  $this->_salt_base;

    $this->setWidgets(array(
      'group_key'      => new sfWidgetFormInputHidden(),
      'collectibles'   => new sfWidgetFormChoice(array('choices'=>$this->getCollectibleIds(),'multiple' => true )),
      'shipping_country_iso3166'
                       => new cqWidgetFormI18nChoiceCountry(array('add_empty' => false, 'culture' => 'en')),
      'note_to_seller' => new sfWidgetFormTextarea(),
      '_nonce_token'   => new IceWidgetNonceToken(array('action' => 'checkout', 'salt' => $_salt))
    ));

    $this->setValidators(array(
      'group_key'      => new sfValidatorString(array('required' => false)),
      'collectibles'   => new sfValidatorPropelChoice(
                             array('model'=>'Collectible','column'=>'id', 'multiple' => true, 'required' => true)),
      'shipping_country_iso3166'
                       => new sfValidatorI18nChoiceCountry(array('required' => true)),
      'note_to_seller' => new sfValidatorString(array('required' => false)),
      '_nonce_token'   => new IceValidatorNonceToken(array('action' => 'checkout', 'salt' => $_salt))
    ));

    // Default to United States
    $this->setDefault('country_iso3166', $this->getObject()->getShippingCountryIso3166());

    $this->widgetSchema->setNameFormat('checkout[%s]');
    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    $this->setDefault('group_key', $this->getObject()->getGroupKey());
    $this->setDefault('shipping_country_iso3166', $this->getObject()->getShippingCountryIso3166());
  }

  private function getCollectibleIds()
  {
    $result = array();
    $order_collectibles = $this->getObject()->getShoppingOrderCollectibles();
    foreach ($order_collectibles as $order_collectible)
    {
      $result[$order_collectible->getCollectibleId()] = $order_collectible->getCollectibleId();
    }
    return $result;
  }
}
