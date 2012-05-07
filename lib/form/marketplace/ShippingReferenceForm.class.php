<?php

/**
 * ShippingReference form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Collectors
 */
class ShippingReferenceForm extends BaseShippingReferenceForm
{
  public function configure()
  {
    $this->widgetSchema->setLabels(array(
        'model' => 'Model Class',
        'model_id' => 'Model ID',
        'country_iso3166' => 'Country',
        'shipping_type' => 'Shipping Type',
    ));
  }
}
