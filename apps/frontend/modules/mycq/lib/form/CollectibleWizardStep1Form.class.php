<?php

class CollectibleWizardStep1Form extends CollectibleEditForm
{
  public function configure()
  {
    $this->setupThumbnailField();
    $this->setupCollectorCollectionsField();
    $this->setupContentCategoryField();
    $this->useFields(array(
      'thumbnail',
      'collection_collectible_list', 'content_category'
    ));

    $this->getWidgetSchema()->setFormFormatterName('Bootstrap');
  }

}
