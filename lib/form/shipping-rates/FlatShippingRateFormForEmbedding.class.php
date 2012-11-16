<?php

class FlatShippingRateFormForEmbedding extends ShippingRateFormForEmbeddig
{

  public function configure()
  {
    parent::configure();

    $this->validatorSchema['flat_rate_in_cents']->setOption('required', false);

    $this->mergePostValidator(
      new FlatShippingRateFormForEmbeddingValidatorSchema(null)
    );
  }

}
