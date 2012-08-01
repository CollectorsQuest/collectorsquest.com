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
    $category_edit_url = sfContext::getInstance()->getController()->genUrl(array(
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
        'extra_html' => '<br/>'.sprintf('<a class="btn btn-mini open-dialog" href="%s" style="margin-top: 3px;">%s</a>',
                        $category_edit_url, 'click to change'),
      ),
      array('style' => 'margin-top: 5px; float: left;')
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
