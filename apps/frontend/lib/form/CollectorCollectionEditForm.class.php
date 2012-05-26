<?php

class CollectorCollectionEditForm extends CollectorCollectionForm
{
  public function configure()
  {
    parent::configure();

    $this->widgetSchema['name']->setAttribute('class', 'input-xlarge');
    $this->widgetSchema['description']->setAttribute('class', 'input-xlarge');

    // Define which fields to use from the base form
    $this->useFields(array(
        'name',
        'description',
        'tags',
    ));
  }

}
