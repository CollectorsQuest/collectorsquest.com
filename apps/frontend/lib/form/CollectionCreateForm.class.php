<?php

class CollectionCreateForm extends CollectorCollectionForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'id'  => new sfWidgetFormInputHidden(),
      'collectible_id'  => new sfWidgetFormInputHidden(),
      'step'       => new sfWidgetFormInputHidden(array('default' => 1)),
    ));

    $this->setValidators(array(
      'id'    => new sfValidatorPropelChoice(
        array('model' => 'CollectorCollection', 'column' => 'id', 'required' => false)
      ),
      'collectible_id'  => new sfValidatorInteger(array('required' => false)),
      'step'  => new sfValidatorInteger(),
    ));

    $this->setupThumbnailField();

    $this->widgetSchema->setNameFormat('collection[%s]');
    $this->widgetSchema->setFormFormatterName('Bootstrap');
  }

}
