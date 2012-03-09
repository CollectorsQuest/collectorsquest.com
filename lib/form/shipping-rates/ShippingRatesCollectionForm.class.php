<?php

/**
 * ShippingFeeCollectionForm is the main form that encompasses all shipping settings.
 *
 * It takes a Collector or a Collection object and sets up all necessary relations
 *
 */
class ShippingRatesCollectionForm extends sfFormPropel
{

  public function __construct($object = null, $options = array(), $CSRFSecret = null)
  {
    if (!in_array(get_class($object), array('Collector', 'Collection')))
    {
      throw new InvalidArgumentException(sprintf(
        'ShippingFeeCollectionForm exects a Collector or Collectible object,
         %s given',
        get_class($object)
      ));
    }

    $this->object = $object;

    parent::__construct($object, $options, $CSRFSecret);
  }

  public function configure()
  {
    $this->setupEmbeddedForms();

    $this->widgetSchema->setNameFormat('shipping_rates_collection[%s]');
  }

  public function getModelName()
  {
    return get_class($this->getObject());
  }

  /**
   * Override doSave() so that it does not modify the main object passed to the form
   * (Collector or Collectible) but only calls updateObjectEmbeddedForms()
   * and saveEmbeddedForms()
   *
   * @param     PropelPDO $con
   */
  protected function doSave($con = null)
  {
    if (null === $con)
    {
      $con = $this->getConnection();
    }

    if (null === $values)
    {
      $values = $this->values;
    }

    $values = $this->processValues($values);

    // embedded forms
    $this->updateObjectEmbeddedForms($values);

    // embedded forms
    $this->saveEmbeddedForms($con);
  }

  protected function setupEmbeddedForms()
  {
    $shipping_rates_by_country = $this->getObject()->getShippingRatesByCountry();

    foreach ($shipping_rates_by_country as $country_code => $shipping_rates)
    {
      $calculation_type = $shipping_rates[0]->getCalculationType();
      $form = new ShippingRatesForCountryCollectionForm(
        $shipping_rates,
        $country_code,
        $calculation_type
      );
      $this->embedForm($form->getNameForEmbedding(), $form);
    }
  }

}
