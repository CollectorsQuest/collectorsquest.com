<?php

class FlatShippingRateFormForEmbedding extends ShippingRateFormForEmbeddig
{

  public function configure()
  {
    parent::configure();

    foreach ($this->validatorSchema->getFields() as $field)
    {
      $field->setOption('required', false);
    }

    $this->mergePostValidator(new FlatShippingRateFormForEmbeddingValidatorSchema(null, array(
    )));
  }

}
