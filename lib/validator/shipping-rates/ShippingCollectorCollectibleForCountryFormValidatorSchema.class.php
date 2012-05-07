<?php

class ShippingCollectorCollectibleForCountryFormValidatorSchema extends sfValidatorSchema
{

  protected function doClean($values)
  {
    $shipping_forms = $values['shipping_rates'];

    if (null === $shipping_forms['shipping_rate_new']['shipping_carrier_service_id'])
    {
      unset ($values['shipping_rates']['shipping_rate_new']);
    }

    return $values;
  }

}