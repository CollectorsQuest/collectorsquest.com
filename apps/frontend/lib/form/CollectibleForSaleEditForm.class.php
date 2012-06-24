<?php

class CollectibleForSaleEditForm extends CollectibleForSaleForm
{
  public function configure()
  {
    parent::configure();

    $this->setupPriceField();
    $this->setupConditionField();

    // add a post validator
    $this->validatorSchema->setPostValidator(new sfValidatorCallback(array(
        'callback' => array($this, 'validateIsReadyField')
      ), array(
        'invalid' => 'You do not have enough credits to post this Collectible to the marketplace!',
    )));

    $this->useFields(array(
      'is_ready',
      'price',
      'condition'
    ));

    $this->getWidgetSchema()->setFormFormatterName('Bootstrap');
    $this->getWidgetSchema()->setNameFormat('collectible_for_sale[%s]');
  }

}
