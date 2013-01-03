<?php

class CollectibleWizardStep1Form extends CollectibleEditForm
{
  public function configure()
  {
    $this->setupCollectorCollectionsField();
    $this->setupContentCategoryField();
    $this->useFields(array('collection_collectible_list', 'content_category'));

    $this->getWidgetSchema()->setFormFormatterName('Bootstrap');
  }

}
