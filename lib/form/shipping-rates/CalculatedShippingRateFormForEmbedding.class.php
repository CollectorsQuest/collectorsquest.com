<?php

class CalculatedShippingRateFormForEmbedding extends ShippingRateFormForEmbeddig
{

  public function unsetFields()
  {
    parent::unsetFields();

    unset ($this['flat_rate_in_cents']);
    unset ($this['is_free_shipping']);
  }

}
