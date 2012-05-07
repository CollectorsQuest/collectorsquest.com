<?php

class ShippingRateFormForEmbeddig extends ShippingRateForm
{

  protected function unsetFields()
  {
    parent::unsetFields();

    unset ($this['id']);
    unset ($this['shipping_reference_id']);
  }

}