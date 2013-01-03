<?php

class CollectibleWizardStep1Form extends BaseCollectibleForm
{
  public function configure()
  {
  //  $this->setupThumbnailField();
  //  $this->setupCollectorCollectionsField();
   // $this->setupContentCategoryField();
    $this->useFields(array(
      'name',
     // 'thumbnail',
   //   'collection_collectible_list', 'content_category'
    ));
  }

}
