<?php

/**
 * ShippingRateCollectorFormForEmbedding
 */
class ShippingRateCollectorFormForEmbedding extends ShippingRateCollectorForm
{

  public function configure()
  {
    parent::configure();

    $this->widgetSchema['collector_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['country_iso3166'] = new sfWidgetFormInputHidden();

    unset ($this['country_iso3166']);
    unset ($this['calculation_type']);

    // we unset the shippingRatePriceRangeValidatorSchema and
    // shippingRateAmountInCentsOrPercentValidatorSchema validators,
    // because they need to be added in a special way by the parent form,
    // in order to work in combination with cqCopyFieldsToEmbeddedFormValidatorSchema
    $this->validatorSchema->setPostValidator(new sfValidatorPass());
  }

  public function getNameForEmbedding()
  {
    return 'shipping_rate_collector_' . $this->getObject()->getId();
  }

}
