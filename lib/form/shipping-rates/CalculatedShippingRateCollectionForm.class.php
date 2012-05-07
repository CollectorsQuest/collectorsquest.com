<?php

class CalculatedShippingRateCollectionForm extends BaseShippingRateCollectionForm
{

  public function configure()
  {
    $this->setupEmbeddedShippingRateForms('CalculatedShippingRateFormForEmbedding');
  }

}