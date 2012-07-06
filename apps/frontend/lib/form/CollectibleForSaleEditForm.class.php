<?php

class CollectibleForSaleEditForm extends CollectibleForSaleForm
{
  public function configure()
  {
    parent::configure();

    $this->setupPriceField();
    $this->setupConditionField();

    // add a post validator
    $this->mergePostValidator(new sfValidatorCallback(array(
        'callback' => array($this, 'validateIsReadyField')
      ), array(
        'invalid' => "Please purchase credits if you'd like to sell this item.",
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
