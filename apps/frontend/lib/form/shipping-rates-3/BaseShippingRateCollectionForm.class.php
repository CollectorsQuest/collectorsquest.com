<?php

abstract class BaseShippingRateCollectionForm extends sfForm
{
  /** @var ShippingReference */
  protected $shipping_reference;

  public function __construct(
    ShippingReference $shipping_reference,
    $options = array(),
    $CSRFSecret = null
  ) {
    $this->shipping_reference = $shipping_reference;

    parent::__construct(array(), $options, $CSRFSecret);
  }

  public function configure()
  {
    $this->setupEmbeddedShippingRateForms();
  }

  protected function setupEmbeddedShippingRateForms($form_class)
  {
    foreach ($this->shipping_reference->getShippingRates() as $k => $shipping_rate)
    {
      $form = new $form_class($shipping_rate);
      $form->widgetSchema->setFormFormatterName('Bootstrap');

      $this->embedForm('shipping_rate_'.$k, $form, '%content%');
    }

    // new shipping option
    $shipping_rate = new ShippingRate();
    $shipping_rate->setShippingReference($this->shipping_reference);

    $form = new $form_class($shipping_rate, array(
        // allow the form to be empty if we have at least 1 shipping rate set
        'empty_allowed' => count($this->shipping_reference->getShippingRates()),
    ));
    $form->widgetSchema->setFormFormatterName('Bootstrap');

    $this->embedForm('shipping_rate_new', $form, '%content%');
  }


}