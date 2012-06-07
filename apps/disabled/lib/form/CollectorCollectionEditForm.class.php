<?php

class CollectorCollectionEditForm extends LegacyCollectorCollectionForm
{
  public function configure()
  {
    parent::configure();

    $this->widgetSchema['collection_category_id'] = new sfWidgetFormPropelChoice(array(
        'model' => 'CollectionCategory',
        'add_empty' => false,
        'order_by' => array('Name', 'Asc'),
        'method' => 'getNameWithParent',
    ));

    // Define which fields to use from the base form
    $this->useFields(array(
        'collection_category_id',
        'name',
        'description',
        'thumbnail',
        'tags',
    ));
  }

  protected function setupThumbnailField()
  {
    parent::setupThumbnailField();
    $this->validatorSchema['thumbnail']->setOption('required', false);
  }

}
