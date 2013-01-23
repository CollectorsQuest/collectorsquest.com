<?php

class CollectibleWizardStep2Form extends CollectibleEditForm
{
  public function configure()
  {
    $this->setupCollectorCollectionsField();

    $category_edit_url = cqContext::getInstance()->getController()->genUrl(array(
      'sf_route' => 'ajax_mycq',
      'section' => 'collectible',
      'page' => 'changeCategory',
      'collectible_id' => $this->getObject()->getId(),
      'wizard' => '1',
    ));
    $this->setupContentCategoryField($category_edit_url);
    $this->setupTagsField();

    $this->widgetSchema['description']->setAttribute('class', 'input-xlarge js-invisible');
    $this->widgetSchema['description']->setAttribute('required', 'required');

    $this->useFields(array('content_category', 'collection_collectible_list', 'description', 'tags'));
    $this->getWidgetSchema()->setFormFormatterName('Bootstrap');
  }

}
