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
  }

  public function getNameForEmbedding()
  {
    return 'shipping_rate_collector_' . $this->getObject()->getId();
  }
}
