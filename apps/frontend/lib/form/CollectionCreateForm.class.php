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

    // Setup the Tags field
    //$this->setupTagsField();
    // Setup the Name field
    //$this->setupNameField();

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
/*
  protected function setupTagsField()
  {
    // pretty ugly hack, but in this case this is the only way
    // to keep the field's state between requests...
    $tags = $this->getObject()->getTags();

    $this->widgetSchema['tags']->setDefault($tags);
    $this->getWidgetSchema()->setHelp(
      'tags', 'Choose at least three descriptive words
               or phrases, separated by commas.'
    );
  }

  protected function setupNameField()
  {
    $this->getWidgetSchema()->setHelp(
      'name', 'Enter a title to describe your entire collection.'
    );
  }*/
}
