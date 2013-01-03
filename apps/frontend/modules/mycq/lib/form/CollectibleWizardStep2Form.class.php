<?php

class CollectibleWizardStep2Form extends CollectibleEditForm
{
  public function configure()
  {
    $this->setupTagsField();

    $this->widgetSchema['name']->setLabel('Item Name');
    $this->widgetSchema['name']->setAttribute('class', 'input-xlarge');
    $this->widgetSchema['name']->setAttribute('required', 'required');

    $this->widgetSchema['description']->setAttribute('class', 'input-xlarge js-invisible');
    $this->widgetSchema['description']->setAttribute('required', 'required');

    $this->useFields(array('name', 'description', 'tags'));
    $this->getWidgetSchema()->setFormFormatterName('Bootstrap');
  }

}
