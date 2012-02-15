<?php

class CollectibleForSaleEditForm extends BaseCollectibleForSaleForm
{
  public function configure()
  {
    $this->useFields(array(
      'price',
      'condition',
      'is_shipping_free',
      'is_ready',
      'quantity',
    ));

    // Get the Collectibles for sale condictions
    $conditions = CollectibleForSalePeer::$conditions;

    // Remove the 'Any' from the array
    unset($conditions['']);

    $this->setWidget('condition', new sfWidgetFormChoice(array('choices' => $conditions)));
    $this->setValidator('condition', new sfValidatorChoice(array('choices' => array_keys($conditions))));

    $this->setValidator('price', new cqValidatorPrice(array('required' => false)));
    $this->getValidator('price')->setOption('required', false);

    $this->setValidator('is_ready', new sfValidatorBoolean(array('required' => false)));

    $this->widgetSchema->setNameFormat('for_sale[%s]');
  }

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    $this->validatorSchema['price']->setOption('required', !empty($taintedValues['is_ready']));

    parent::bind($taintedValues, $taintedFiles);
  }
}
