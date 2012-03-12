<?php

/**
 * ShippingFeeCollectionForm is the main form that encompasses all shipping settings.
 *
 * It takes a Collector or a Collection object and sets up all necessary relations
 *
 */
class ShippingRatesCollectionForm extends sfFormPropel
{

  /**
   * @param     Collector|Collectible $object
   * @param     array $options
   * @param     string $CSRFSecret
   */
  public function __construct(
    $object = null,
    $options = array(),
    $CSRFSecret = null
  ) {
    if (!in_array(get_class($object), array('Collector', 'Collection')))
    {
      throw new InvalidArgumentException(sprintf(
        'ShippingFeeCollectionForm exects a Collector or Collectible object,
         %s given',
        get_class($object)
      ));
    }

    // we need to set the object here because sfFormPropel::__construct checks
    // it against the result of getModelName, but our version returns the class
    // of $this->object (because there are two possibilities)
    $this->object = $object;

    parent::__construct($object, $options, $CSRFSecret);
  }

  /**
   * Main form configuration
   */
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
   * As this form acts as a holder for the actual data, most of the
   * important logic is contained in this method
   */
  protected function setupEmbeddedForms()
  {
    $form = new ShippingRatesForDomesticShippingForm(array(), array(
        'parent_object' => $this->getObject(),
        'shipping_rates' => $this->getObject()->getShippingRatesDomestic(),
        'tainted_request_values' => $this->getTaintedRequestValue('country_domestic'),
    ));

    $this->embedForm($form->getNameForEmbedding(), $form);

    $shipping_rates_by_country = $this->getObject()
      ->getShippingRatesGroupedByCountryCode();

    /* */
    foreach ($shipping_rates_by_country as $country_code => $shipping_rates)
    {
      $calculation_type = $shipping_rates[0]->getCalculationType();
      $form = new ShippingRatesForCountryForm($defaults = array(
           // no defaults
        ), $options = array(
          'parent_object'  => $this->getObject(),
          'shipping_rates' => $shipping_rates,
          'country_code'   => $country_code,
          'calculation_type' => $calculation_type,
          'tainted_request_values' =>
              $this->getTaintedRequestValue('country_'.$country_code),
      ));

      $this->embedForm($form->getNameForEmbedding(), $form);
    }
  }

  /**
   * Override doSave() so that it does not modify the main object
   * passed to the form (Collector or Collectible),
   * but only calls updateObjectEmbeddedForms() and saveEmbeddedForms()
   *
   * @param     PropelPDO $con
   */
  protected function doSave($con = null)
  {
    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $values = $this->processValues($this->getValues());

    // embedded forms
    $this->updateObjectEmbeddedForms($values);

    // embedded forms
    $this->saveEmbeddedForms($con);
  }

  /**
   * Easy getter for values set in the tainted_request_values option,
   * which should be an associative array
   *
   * @param     string $value_name
   * @param     mixed $default
   * @return    mixed
   */
  protected function getTaintedRequestValue($value_name, $default = null)
  {
    $tainted_request_values = $this->getOption('tainted_request_values', array());

    if (isset($tainted_request_values[$value_name]))
    {
      return $tainted_request_values[$value_name];
    }
    else
    {
      return $default;
    }
  }

}
