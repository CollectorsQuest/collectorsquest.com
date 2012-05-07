<?php

class FlatShippingRateCollectionForm extends BaseShippingRateCollectionForm
{

  public function configure()
  {
    $this->setupEmbeddedShippingRateForms('FlatShippingRateFormForEmbedding');
  }

}