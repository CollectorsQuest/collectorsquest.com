<?php

class CollectibleForSaleBuyForm extends sfFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'collectible_id'   => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorPropelChoice(array('model' => 'CollectibleForSale', 'column' => 'id', 'required' => true)),
      'collectible_id'   => new sfValidatorPropelChoice(array('model' => 'Collectible', 'column' => 'id', 'required' => true)),
    ));

    $this->widgetSchema->setNameFormat('collectible_for_sale[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CollectibleForSale';
  }

}
