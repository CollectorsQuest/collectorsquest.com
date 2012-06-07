<?php

class CollectibleForSaleBuyForm extends sfFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'collectible_id'   => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
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
