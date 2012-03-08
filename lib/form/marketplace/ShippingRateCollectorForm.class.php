<?php

/**
 * ShippingRateCollector form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Collectors
 *
 * @method     ShippingRateCollector getObject()
 */
class ShippingRateCollectorForm extends BaseShippingRateCollectorForm
{

  public function configure()
  {
    $this->validatorSchema['price_range_min']   = new sfValidatorInteger(array('min' => 0, 'max' => 2147483647));
    $this->validatorSchema['price_range_max']   = new sfValidatorInteger(array('min' => 0, 'max' => 2147483647));
    $this->validatorSchema['amount_in_cents']   = new sfValidatorInteger(array('min' => 0, 'max' => 2147483647));
    $this->validatorSchema['amount_in_percent'] = new sfValidatorInteger(array('min' => 0, 'max' => 99));
  }

}
