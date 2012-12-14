<?php

class ShoppingOrderShippingForm extends BaseForm
{
  /**
   * @var null|ShoppingOrder
   */
  private $shopping_order = null;

  public function __construct(ShoppingOrder $shopping_order, $defaults = array(), $options = array())
  {
    // Set the current ShoppingOrder
    $this->shopping_order = $shopping_order;

    parent::__construct($defaults, $options);
  }

  public function setup()
  {
    /** @var $sf_user cqFrontendUser */
    $sf_user = cqContext::getInstance()->getUser();

    $this->setWidgets(array(
      'buyer_email'  => new sfWidgetFormInputText(
        array(), array('required' => 'required')
      ),
      'buyer_phone'  => new sfWidgetFormInputText()
    ));

    $this->setValidators(array(
      'buyer_email'  => new sfValidatorEmail(array('max_length' => 128, 'required' => true)),
      'buyer_phone'  => new sfValidatorString(array('max_length' => 50, 'required' => false)),
    ));

    // Try to figure out the default buyer email
    $buyer_email = $this->shopping_order->getBuyerEmail() ?
      $this->shopping_order->getBuyerEmail() :
      $sf_user->getAttribute('buyer_email', $sf_user->getCollector()->getEmail(), 'shopping');

    $this->setDefaults(array(
      'buyer_email' => $buyer_email,
      'buyer_phone' => $this->shopping_order->getShippingPhone()
    ));

    $this->widgetSchema->setFormFormatterName('Bootstrap');
    $this->widgetSchema->setNameFormat('shopping_order[%s]');
    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $defaults = array(
      'address_id' =>$this->shopping_order->getShippingAddressId(),
      'full_name' => $this->shopping_order->getShippingFullName(),
      'address_line_1' => $this->shopping_order->getShippingAddressLine1(),
      'address_line_2' => $this->shopping_order->getShippingAddressLine2(),
      'city' => $this->shopping_order->getShippingCity(),
      'state_region' => $this->shopping_order->getShippingStateRegion(),
      'zip_postcode' => $this->shopping_order->getShippingZipPostcode(),
      'country_iso3166' => $this->shopping_order->getShippingCountryIso3166()
    );

    // If not authenticated, try to set the defaults from the session
    if (!$sf_user->isAuthenticated())
    {
      $session_defaults = (array) $sf_user->getAttribute(
        'shipping_address', array(), 'shopping'
      );

      $use_session_defaults =
        isset($session_defaults['country_iso3166']) &&
        ($session_defaults['country_iso3166'] == $defaults['country_iso3166']);

      foreach (array_keys($defaults) as $key)
      {
        if (!empty($defaults[$key]) && $key !== 'country_iso3166')
        {
          $use_session_defaults = false;
          break;
        }
      }

      $defaults = $use_session_defaults ? $session_defaults : $defaults;
    }

    $shipping_address = new ShippingAddressForm();
    $shipping_address->setDefaults($defaults);
    $shipping_address->widgetSchema->setFormFormatterName('Bootstrap');
    $this->embedForm('shipping_address', $shipping_address, '%content%');

    $this->mergePostValidator(new sfValidatorCallback(array(
        'callback' => array($this, 'validateShippingCountry'),
      ), array(
        'invalid' => 'The seller does not ship to this country',
    )));

    parent::setup();
  }

  public function configure()
  {
    $this->widgetSchema['buyer_email']->setLabel('Email Address');
    $this->widgetSchema['buyer_phone']->setLabel('Telephone Number');

    $this->validatorSchema['buyer_email']->setMessage('required',
      'Your email address is used to send you an order confirmation');

    $this->validatorSchema['buyer_email']->setMessage('invalid',
      'This email address does not seem to be valid format');

    $states = $this->getStatesForCountry($this->shopping_order->getShippingCountryIso3166());

    if (count($states))
    {
      // Setup only widget and use post validator because country can be changed
      $this->widgetSchema['shipping_address']['state_region'] =  new sfWidgetFormChoice(
        array(
          'choices' => array(0 => null) + $states,
          'label' => $this->widgetSchema['shipping_address']['state_region']->getLabel()
        ), array('required' => 'required')
      );
    }

    $this->mergePostValidator(new sfValidatorCallback(array(
      'callback' => array($this, 'validateShippingCountryRegion'),
    ), array(
      'invalid' => 'Sorry this State / Province is wrong',
    )));
  }

  public function save()
  {
    $values = $this->getValues();
    $shipping_address = $values['shipping_address'];

    $this->shopping_order->setBuyerEmail($values['buyer_email']);
    $this->shopping_order->setShippingFullName($shipping_address['full_name']);
    $this->shopping_order->setShippingPhone($values['buyer_phone']);
    $this->shopping_order->setShippingAddressLine1($shipping_address['address_line_1']);
    $this->shopping_order->setShippingAddressLine2($shipping_address['address_line_2']);
    $this->shopping_order->setShippingCity($shipping_address['city']);
    $this->shopping_order->setShippingStateRegion($shipping_address['state_region']);
    $this->shopping_order->setShippingZipPostcode($shipping_address['zip_postcode']);
    $this->shopping_order->setShippingCountryIso3166($shipping_address['country_iso3166']);

    // update shopping cart collectible shipping based on new
    // shipping address country
    foreach ($this->shopping_order->getShoppingOrderCollectibles() as $shopping_order_collectible)
    {
      /** @var $shopping_cart_collectible ShoppingCartCollectible */
      $shopping_cart_collectible = ShoppingCartCollectiblePeer::retrieveByPK(
        $this->shopping_order->getShoppingCartId(), $shopping_order_collectible->getCollectibleId()
      );
      $shopping_cart_collectible
        ->setShippingCountryIso3166($shipping_address['country_iso3166'])
        ->updateShippingFeeAmountFromCountryCode()
        ->save();
    }

    try
    {
      $this->shopping_order->save();

      /** @var $sf_user cqFrontendUser */
      $sf_user = cqContext::getInstance()->getUser();

      // Save the buyer email address for later purchases
      $sf_user->setAttribute('buyer_email', $values['buyer_email'], 'shopping');

      // Save the shipping address for later purchases
      $sf_user->setAttribute('shipping_address', $shipping_address, 'shopping');

      return true;
    }
    catch (PropelException $e)
    {
      return false;
    }
  }

  /**
   * Validate if shipping to the selected country is allowed
   *
   * @param     sfValidatorBase $validator
   * @param     array $values
   * @param     array $arguments
   *
   * @throws    sfValidatorErrorSchema
   * @return    array() validated values
   */
  public function validateShippingCountry(sfValidatorBase $validator, $values, $arguments = array())
  {
    if (!isset($values['shipping_address']))
    {
      return $values;
    }

    $country_code = $values['shipping_address']['country_iso3166'];
    foreach ($this->shopping_order->getShoppingOrderCollectibles() as $shopping_order_collectible)
    {
      $shipping_reference = $shopping_order_collectible->getShippingReference($country_code);

      if ($shipping_reference &&
        ShippingReferencePeer::SHIPPING_TYPE_NO_SHIPPING == $shipping_reference->getShippingType())
      {
        throw new sfValidatorErrorSchema($validator, array(
          'shipping_address' => new sfValidatorErrorSchema($validator, array(
            'country_iso3166' => new sfValidatorError($validator, 'invalid'),
          )),
        ));
      }

    }

    return $values;
  }

  /**
   * Validate State/Region for country
   *
   * @param sfValidatorBase $validator
   * @param $values
   * @param array $arguments
   *
   * @throws    sfValidatorErrorSchema
   * @return    array() validated values
   */
  public function validateShippingCountryRegion(sfValidatorBase $validator, $values, $arguments = array())
  {
    if (!isset($values['shipping_address']))
    {
      return $values;
    }
    $states = $this->getStatesForCountry($values['shipping_address']['country_iso3166']);
    // Validate only if ve have states for country at DB
    if (count($states))
    {
      $state = $values['shipping_address']['state_region'];
      if (!in_array($state, array_keys($states)))
      {
        throw new sfValidatorErrorSchema($validator, array(
          'shipping_address' => new sfValidatorErrorSchema($validator, array(
            'state_region' => new sfValidatorError($validator, 'invalid'),
          )),
        ));
      }
    }

    return $values;
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
