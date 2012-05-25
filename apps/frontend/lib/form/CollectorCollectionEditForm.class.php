<?php

class CollectorCollectionEditForm extends CollectorCollectionForm
{
  public function configure()
  {
    parent::configure();

    $this->widgetSchema['content_category_id'] = new cqWidgetFormPropelChoiceByNestedSet(array(
      'label' => 'Category',
      'model' => 'ContentCategory',
      'add_empty' => true,
    ));

    $this->widgetSchema['name']->setAttribute('class', 'input-xlarge');
    $this->widgetSchema['description']->setAttribute('class', 'input-xlarge');

    // Define which fields to use from the base form
    $this->useFields(array(
        'content_category_id',
        'name',
        'description',
        'tags',
    ));
  }

}
