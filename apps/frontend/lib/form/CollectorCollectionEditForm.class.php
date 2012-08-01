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
    $this->setupContentCategoryPlainField();

    // Define which fields to use from the base form
    $this->useFields(array(
        'content_category_plain',
        'name',
        'thumbnail',
        'description',
        'tags',
    ));

    if ($this->getObject()->hasThumbnail())
    {
      $this->offsetUnset('thumbnail');
    }
  }

  protected function setupContentCategoryPlainField()
  {
    $this->widgetSchema['content_category_plain'] = new cqWidgetFormPlain(array(
        'label' => 'Content Category',
      ), array(
        'required' => 'required',
    ));
    $this->validatorSchema['content_category_plain'] = new sfValidatorPass();
  }

  protected function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    $this->setDefault('content_category_plain',
      $this->getObject()->getContentCategory()->getPath());
  }

}
