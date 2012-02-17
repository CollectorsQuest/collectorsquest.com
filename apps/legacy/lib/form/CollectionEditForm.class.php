<?php

class CollectionEditForm extends BaseCollectionForm
{
  public function configure()
  {
    $collection = $this->getObject();

    $this->getWidgetSchema()->setFormFormatterName('legacy');

    $this->widgetSchema['collection_category_id'] = new sfWidgetFormPropelChoice(array(
      'model' => 'CollectionCategory', 'add_empty' => false,
      'order_by' => array('Name', 'Asc'), 'method' => 'getNameWithParent'
    ));

    $this->widgetSchema['thumbnail'] = new sfWidgetFormInputFile();
    $this->validatorSchema['thumbnail'] = new sfValidatorFile(array('required' => false));

    $this->widgetSchema['tags'] = new sfWidgetFormInput();
    $this->validatorSchema['tags'] = new sfValidatorPass();

    // Define which fields to use from the base form
    $this->useFields(array(
      'collection_category_id', 'name', 'description', 'thumbnail', 'tags'
    ));
  }
}
