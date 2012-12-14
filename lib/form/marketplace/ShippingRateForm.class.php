<?php

/**
 * ShippingRate form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Collectors
 */
class ShippingRateForm extends BaseShippingRateForm
{
  public function configure()
  {
    $this->setupFlatRateInCentsField();
    $this->setupCombinedFlatRateInCentsField();
    $this->setupShippingCarrierServiceIdField();

    $this->unsetFields();
  }

  protected function setupFlatRateInCentsField()
  {
    $this->widgetSchema['flat_rate_in_cents'] = new cqWidgetFormInputCentsToUsd(array(
        'label' => 'Flat rate',
        'show_zero_as_empty' => true,
    ));
    $this->setDefault('flat_rate_in_cents', sprintf('%01.2f', $this->getObject()->getFlatRateInUSD()));
    $this->validatorSchema['flat_rate_in_cents'] = new cqValidatorUSDtoCents();
  }

  protected function setupCombinedFlatRateInCentsField()
  {
    $this->widgetSchema['combined_flat_rate_in_cents'] = new cqWidgetFormInputCentsToUsd(array(
        'label' => 'Combined Flat Rate',
        'show_zero_as_empty' => true,
    ));
    $this->setDefault('combined_flat_rate_in_cents', sprintf('%01.2f', $this->getObject()->getCombinedFlatRateInUSD()));
    $this->validatorSchema['combined_flat_rate_in_cents'] = new cqValidatorUSDtoCents();
  }

  protected function setupShippingCarrierServiceIdField()
  {
    $this->widgetSchema['shipping_carrier_service_id'] = new sfWidgetFormSelect(array(
        'choices' => $this->getShippingCarrierServiceIdChoices(new Criteria()),
    ));
  }

  protected function getShippingCarrierServiceIdChoices(Criteria $c)
  {
    $shipping_services = ShippingCarrierServicePeer::doSelect($c);
    $choices = array();

    foreach ($shipping_services as $shipping_service)
    {
      $choices[$shipping_service->getCarrier()][$shipping_service->getId()] = $shipping_service;
    }

    return array('' => '-') + $choices;
  }

}
