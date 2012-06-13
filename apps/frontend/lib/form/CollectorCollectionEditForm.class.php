<?php

class CollectorCollectionEditForm extends CollectorCollectionForm
{
  public function configure()
  {
    parent::configure();

    $this->widgetSchema['name']->setAttribute('class', 'input-xlarge');
    $this->widgetSchema['name']->setAttribute('required', 'required');
    $this->widgetSchema['description']->setAttribute('class', 'input-xlarge js-invisible');
    $this->widgetSchema['description']->setAttribute('required', 'required');

    $this->setupThumbnailField();

    // Define which fields to use from the base form
    $this->useFields(array(
        'name',
        'thumbnail',
        'description',
        'tags',
    ));
  }

}
