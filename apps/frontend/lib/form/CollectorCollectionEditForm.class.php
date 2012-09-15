<?php

class CollectorCollectionEditForm extends CollectorCollectionForm
{
  public function configure()
  {
    parent::configure();

    $this->setupNameField();
    $this->setupDescriptionField();
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

  protected function setupNameField()
  {
    $this->widgetSchema['name']->setAttribute('class', 'input-xlarge');
    $this->widgetSchema['name']->setAttribute('required', 'required');

    $this->validatorSchema['name'] = new cqValidatorName(
      array('required' => true),
      array('invalid' => 'You need to use more descriptive name for your collection!
                          (is it the camera auto generated name?)')
    );
  }

  protected function setupDescriptionField()
  {
    $this->widgetSchema['description']->setAttribute('class', 'input-xlarge js-invisible');
    $this->widgetSchema['description']->setAttribute('required', 'required');

    $this->getWidgetSchema()->setHelp(
      'description', 'Add more details about your collection. (You can also change this later!)'
    );
  }

  protected function setupContentCategoryPlainField()
  {
    $category_edit_url = cqContext::getInstance()->getController()->genUrl(array(
        'sf_route' => 'ajax_mycq',
        'section' => 'collection',
        'page' => 'changeCategory',
        'collection_id' => $this->getObject()->getId(),
    ));

    $this->widgetSchema['content_category_plain'] = new cqWidgetFormPlain(
      array(
        'label' => 'Category:',
        'content_tag' => 'div',
        'default_html' => '<span>&nbsp;</span>',
        'extra_html' => sprintf('<a class="btn btn-mini open-dialog" href="%s" style="margin-top: 3px;">%s</a>',
                        $category_edit_url, 'click to change'),
      ),
      array('style' => 'margin-top: 5px;')
    );
    $this->validatorSchema['content_category_plain'] = new sfValidatorPass();
  }

  protected function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    $this->setDefault('content_category_plain',
      $this->getObject()->getContentCategory()
      ? $this->getObject()->getContentCategory()->getPath()
      : 'No category selected');
  }

}
