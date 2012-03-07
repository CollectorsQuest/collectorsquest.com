<?php

class ShippingRatesForCountryCollectionForm extends sfForm
{
  protected $country_code;
  protected $shipping_rates;

  public function __construct(
    $shipping_rates,
    $country_code,
    $options = array(),
    $CSRFSecret = null
  ) {

    $this->country_code = $country_code;
    $this->shipping_rates = $shipping_rates;

    parent::__construct(array(), $options, $CSRFSecret);
  }

  public function configure()
  {
    $this->setupEmbeddedForms();
  }

  public function getNameForEmbedding()
  {
    // if empty country code specified default to ZZ = Unknown Country or Region
    return 'country_' . ($this->country_code ?: 'zz');
  }

  protected function setupEmbeddedForms()
  {
    /* @var $shipping_rate ShippingRate */
    foreach ($this->shipping_rates as $shipping_rate)
    {
      $form_class_name = $this->getFormClassForShippingRate($shipping_rate);
      $form = new $form_class_name($shipping_rate);

      $this->embedForm($form->getNameForEmbedding(), $form);
    }
  }

  protected function getFormClassForShippingRate(ShippingRate $shipping_rate)
  {
    if ($shipping_rate instanceof ShippingRateCollector)
    {
      return 'ShippingRateCollectorFormForEmbedding';
    }

    if ($shipping_rate instanceof ShippingRateCollectible)
    {
      return 'ShippingRateCollectibleFormForEmbedding';
    }
  }

}