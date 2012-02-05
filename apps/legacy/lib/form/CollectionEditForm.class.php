<?php

class CollectionEditForm extends BaseCollectionForm
{
  public function configure()
  {
    $collection = $this->getObject();

    $this->getWidgetSchema()->setFormFormatterName('legacy');

    $this->validatorSchema['collector_id'] = new sfValidatorPass();
    $this->validatorSchema['slug'] = new sfValidatorPass();

    $this->widgetSchema['collection_category_id'] = new sfWidgetFormInputHidden();

    $this->widgetSchema['thumbnail'] = new sfWidgetFormInputFile();
    $this->validatorSchema['thumbnail'] = new sfValidatorFile(array('required' => false));

    $this->widgetSchema['tags'] = new sfWidgetFormInput();
    $this->validatorSchema['tags'] = new sfValidatorPass();

    unset($this->widgetSchema['graph_id']);
    unset($this->validatorSchema['graph_id']);
  }
}
