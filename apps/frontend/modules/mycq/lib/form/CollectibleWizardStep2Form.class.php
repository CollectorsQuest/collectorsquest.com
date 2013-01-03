<?php

class CollectibleWizardStep2Form extends CollectibleEditForm
{
  public function configure()
  {
    $this->setupTagsField();
    $this->useFields(array('name', 'tags', 'description'));
  }

}
