<?php

class CollectionCreateForm extends BaseCollectionForm
{
  public function configure()
  {
    $this->getWidgetSchema()->setFormFormatterName('legacy');

    $this->validatorSchema['collector_id'] = new sfValidatorPass();
    $this->validatorSchema['slug'] = new sfValidatorPass();

    $this->widgetSchema['thumbnail'] = new sfWidgetFormInputFile();
    $this->validatorSchema['thumbnail'] = new sfValidatorFile();

    $this->widgetSchema['tags'] = new sfWidgetFormSelectMany(array('choices' => array()));
    $this->validatorSchema['tags'] = new sfValidatorPass();

    unset($this->widgetSchema['graph_id']);
    unset($this->validatorSchema['graph_id']);
  }
}
