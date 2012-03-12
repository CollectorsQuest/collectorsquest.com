<?php

class ShippingRatesForCountryForm extends sfForm
{

  /**
   * Setup is executed before configure. Make sure the form is ready to
   * be configured (all required options are set)
   */
  public function setup()
  {
    // check if parent object is of the required type
    $parent_object = $this->getParentObject();
    if ( !($parent_object instanceof Collector
           || $parent_object instanceof Collectible) )
    {
      throw new Exception(sprintf(
        '%s requires the parent_object option to be set and to an instance
         of either Collector or Collectible, "%s" given',
        __CLASS__,
        'object' == gettype($parent_object) ? get_class($parent_object) : gettype($parent_object)
      ));
    }
  }

  /**
   * Configure the form
   */
  public function configure()
  {
    // Setup normal fields
    $this->setupCountryIso3166Field();
    $this->setupCalculationTypeField();

    // setup embedded ShippingRate forms
    $this->setupEmbeddedForms();

    // Add the cqCopyFieldsToEmbeeddedForm validator schema and setup it
    // to copy the country_iso3166 and calculation_type fields
    $this->mergePostValidator(new cqCopyFieldsToEmbeddedFormValidatorSchema(null,array(
        'fields_to_copy' => array(
            'country_iso3166',
            'calculation_type',
         ),
        'embedded_form_names' => array_keys($this->embeddedForms),
    )));

    // add the shipping rate price range validator
    $this->mergePostValidator(new shippingRateCollectionPriceRangeValidatorSchema(null, array(
        'embedded_form_names' => array_keys($this->embeddedForms),
    )));

    // add a combination of shipping rate amount in cents or percent
    // and price range validator to every embedded form
    $this->addPostValidatorToEmbeddedForms(new sfValidatorAnd(array(
        new shippingRateAmountInCentsOrPercentValidatorSchema(),
        new shippingRatePriceRangeValidatorSchema(),
      ), array(
        // do not execute the second validator if the first fails
        'halt_on_error' => true,
        'required' => false,
    )));

    $embedded_form_class_name = $this->getShippingRateDerivedClassForParentObject(
      $this->getOption('parent_object')
    );
  }

  /**
   * Setup the country_iso3166 field
   */
  protected function setupCountryIso3166Field()
  {
    $this->widgetSchema['country_iso3166'] = new cqWidgetFormI18nChoiceCountry(array(
        'add_worldwide' => true,
    ));
    $this->widgetSchema['country_iso3166']->setLabel('Country');
    $this->validatorSchema['country_iso3166'] = new sfValidatorPropelChoice(array(
        'model' => 'GeoCountry',
        'column' => 'iso3166',
    ));
  }

  /**
   * Setup the calculation_type field
   */
  protected function setupCalculationTypeField()
  {
    $calculation_types = ShippingRatePeer::getValueSet(ShippingRatePeer::CALCULATION_TYPE);

    $this->widgetSchema['calculation_type'] = new sfWidgetFormChoice(array(
        'choices' => array_combine($calculation_types, $calculation_types),
    ));

    $this->validatorSchema['calculation_type'] = new sfValidatorChoice(array(
        'choices' => $calculation_types,
    ));
  }

  /**
   * Return the name for embedding, calculated based on the country_code option
   *
   * @return    string
   */
  public function getNameForEmbedding()
  {
    // if empty country code specified default to ZZ = Unknown Country or Region
    return 'country_' . $this->getOption('country_code', 'ZZ');
  }

  /**
   * Merge defaults with options
   * @return    array
   */
  public function getDefaults()
  {
    $defaults = parent::getDefaults();
    return array_merge(array(
        'country_iso3166' => $this->getCountryCodeForDefaults(),
        'calculation_type' => $this->getCalculationTypeForDefaults(),
    ), $defaults);
  }

  /**
   * If the "shipping_rates" option was set (ie, we are editing an existing
   * collection of shipping rates for a particular country) get the country
   * code from them, othewize use calculation_type default
   *
   * @return    string ShippingRatePeer::CALCULATION_TYPE
   */
  protected function getCalculationTypeForDefaults()
  {
    $shipping_rates = $this->getOption('shipping_rates', array());
    if (count($shipping_rates))
    {
      return $shipping_rates[0]->getCalculationType();
    }
    else
    {
      return ShippingRatePeer::DEFAULT_CALCULATION_TYPE;
    }
  }

  /**
   * Get the country code to be used in the defaults. If the country_code
   * option was not set when the form object was created default to ZZ
   *
   * @return    string
   */
  protected function getCountryCodeForDefaults()
  {
    return $this->getOption('country_code', 'ZZ');
  }

  /**
   * Setup all embedded forms
   */
  protected function setupEmbeddedForms()
  {
    $this->embedExistingShippingRates();
  }

  /**
   * Embed shipping rates if they were set as an option to this form.
   * Return the number of shipping rates embedded
   *
   * @return    integer
   */
  protected function embedExistingShippingRates()
  {
    $shipping_rates = $this->getOption('shipping_rates', array());
    $embedded_form_class_name = $this->getEmbeddedFormClassForParentObject(
      $this->getOption('parent_object')
    );

    // embed all of them
    foreach ($shipping_rates as $shipping_rate)
    {
      /* @var $shipping_rate ShippingRate */
      $form = $this->getNewShippingRateFormForEmbedding($shipping_rate);
      $this->embedForm($form->getNameForEmbedding(), $form);
    }

    return count($shipping_rates);
  }

  /**
   * Get a new ShippingRate form for embedding object, based on the parent object's
   * class with some custom defaults set
   *
   * @param     ShippingRate $shipping_rate
   * @param     array $options
   *
   * @return    ShippingRateCollectorFormForEmbedding|ShippingRateCollectibleFormForEmbedding
   */
  protected function getNewShippingRateFormForEmbedding(ShippingRate $shipping_rate, $options = array())
  {
    $embedded_form_class_name = $this->getEmbeddedFormClassForParentObject(
      $this->getParentObject());

    // create and return the form
    return new $embedded_form_class_name($shipping_rate, array_merge(array(
        'current_calculation_type' =>
            $this->getTaintedRequestValue('calculation_type'),
      ),
      $options
    ));
  }

  /**
   * Return the parent object, for which we are setting a shipping rate.
   * It will be either a Collector or a Collectible
   *
   * @return    Collector|Collectible
   */
  protected function getParentObject()
  {
    return $this->getOption('parent_object', null);
  }

  /**
   * Return the proper form class for a ShippingRate derived object
   * based on the parent object option
   *
   * @param     Collector|Collectible $object
   * @return    string
   */
  protected function getEmbeddedFormClassForParentObject($object)
  {
    if ($object instanceof Collector)
    {
      return 'ShippingRateCollectorFormForEmbedding';
    }

    if ($object instanceof Collectible)
    {
      return 'ShippingRateCollectibleFormForEmbedding';
    }
  }

  /**
   * Return the proper object class for a ShippingRate based on the parent object
   *
   * @param     Collector|Collectible $object
   * @return    string
   */
  protected function getShippingRateDerivedClassForParentObject($object)
  {
    if ($object instanceof Collector)
    {
      return 'ShippingRateCollector';
    }

    if ($object instanceof Collectible)
    {
      return 'ShippingRateCollectible';
    }
  }

  /**
   * Return the proper object class for a ShippingRate based on the parent object
   *
   * @param     Collector|Collectible $object
   * @return    string
   */
  protected function getSetterMethodForEmbeddedObject($object)
  {
    if ($object instanceof Collector)
    {
      return 'setCollector';
    }

    if ($object instanceof Collectible)
    {
      return 'setCollectible';
    }
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

  /**
   * Add a as a post validator to every embedded form
   *
   * @param     sfValidatorBase $validator
   */
  protected function addPostValidatorToEmbeddedForms(sfValidatorBase $validator)
  {
    $embeddedFormsPostValidators = array();
    foreach ( array_keys($this->embeddedForms) as $embedded_form )
    {
      $embeddedFormsPostValidators[$embedded_form] = clone $validator;
    }

    $this->mergePostValidator(new sfValidatorSchema($embeddedFormsPostValidators, array(
        'allow_extra_fields' => true,
        'filter_extra_fields' => false,
    )));
  }

  /**
   * Merges a validator with the current post validators.
   *
   * Overloaeded to set "halt on error" option to true
   *
   * @param     sfValidatorBase $validator A validator to be merged
   */
  public function mergePostValidator(sfValidatorBase $validator = null)
  {
    if (null === $validator)
    {
      return;
    }

    if (null === $this->validatorSchema->getPostValidator())
    {
      $this->validatorSchema->setPostValidator($validator);
    }
    else
    {
      $this->validatorSchema->setPostValidator(new sfValidatorAnd(array(
        $this->validatorSchema->getPostValidator(),
        $validator,
      ), array(
          // add halt on error to prevent propagation of errors to lower validators
          'halt_on_error' => true,
      )));
    }
  }

}
