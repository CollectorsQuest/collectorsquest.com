<?php

/**
 * ShippingRateCollectibleFormForEmbedding
 *
 */
class ShippingRateCollectibleFormForEmbedding extends ShippingRateCollectibleForm
{

  public function configure()
  {
    parent::configure();

    $this->widgetSchema['collectible_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['country_iso3166'] = new sfWidgetFormInputHidden();

    $this->mergePostValidator(new shippingRatePriceRangeValidatorSchema());
    $this->mergePostValidator(new shippingRateAmountInCentsOrPercentValidatorSchema());
  }

  public function getNameForEmbedding()
  {
    return 'shipping_rate_collectible_' . $this->getObject()->getId();
  }
}
