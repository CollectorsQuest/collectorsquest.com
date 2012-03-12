<?php

class ShippingRatesForCountryCollectionForm extends sfForm
{

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
    )));
  }

  /**
   * Merges a validator with the current post validators.
   *
   * Overloaeded to halt on error
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
        'country_iso3166' => $this->getOption('country_code', 'ZZ'),
        'calculation_type' => $this->getOption('calculation_type'),
    ), $defaults);
  }

  protected function setupCountryField()
  {
    if ($this->getOption('new_form', false))
    {
      $this->widgetSchema['country_iso3166'] = new cqWidgetFormI18nChoiceCountry(array(
          'add_worldwide' => true,
      ));
    }
    else
    {
      $this->widgetSchema['country_iso3166'] = new cqWidgetFormPlain(array(
          'content_tag' => 'span',
          'render_callback' => function($country_code) {
            $sf_culture = sfContext::getInstance()->getUser()->getCulture();
            $country = sfCultureInfo::getInstance($sf_culture)->getCountry($country_code);
            return $country;
          }
      ));
    }
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

    if ($this->getOption('new_form', false))
    {
      $this->widgetSchema['calculation_type'] = new sfWidgetFormChoice(array(
          'choices' => array_combine($calculation_types, $calculation_types),
      ));
    }
    else
    {
      $this->widgetSchema['calculation_type'] = new cqWidgetFormPlain(array(
          'content_tag' => 'span',
      ));
    }

    $this->validatorSchema['calculation_type'] = new sfValidatorChoice(array(
        'choices' => $calculation_types,
    ));
  }

  /**
   * Setup all embedded ShippingRate fomrs
   */
  protected function setupEmbeddedForms()
  {
    $embedded_form_class_name = $this->getEmbeddedFormClassForParentObject(
      $this->getOption('parent_object')
    );
    /* @var $shipping_rate ShippingRate */
    foreach ($this->getOption('shipping_rates', array()) as $shipping_rate)
    {
      $form = new $embedded_form_class_name($shipping_rate);

      $this->embedForm($form->getNameForEmbedding(), $form);
    }
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


}