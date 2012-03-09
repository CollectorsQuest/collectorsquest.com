<?php

class ShippingRatesForCountryCollectionForm extends sfForm
{

  public function configure()
  {
    $this->setupCountryField();
    $this->setupCalculationTypeField();
    $this->setupEmbeddedForms();

    $this->mergePostValidator(new cqCopyFieldsToEmbeddedFormValidatorSchema(null, array(
        'fields_to_copy' => array(
            'country_iso3166',
            'calculation_type',
         ),
        'embedded_form_names' => array_keys($this->embeddedForms),
    )));

    $this->addPostValidatorToEmbeddedForms(new sfValidatorAnd(array(
        new shippingRateAmountInCentsOrPercentValidatorSchema(),
        new shippingRatePriceRangeValidatorSchema(),
      ), array(
        // do not execute the second validator if the first fails
        'halt_on_error' => true,
    )));
  }

  public function getNameForEmbedding()
  {
    // if empty country code specified default to ZZ = Unknown Country or Region
    return 'country_' . $this->getOption('country_code', 'ZZ');
  }

  public function getDefaults()
  {
    $defaults = parent::getDefaults();
    return array_merge(array(
        'country_iso3166' => $this->getOption('country_code', null),
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