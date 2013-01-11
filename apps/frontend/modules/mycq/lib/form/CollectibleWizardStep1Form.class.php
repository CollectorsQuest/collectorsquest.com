<?php

class CollectibleWizardStep1Form extends BaseCollectibleForm
{

  public function configure()
  {
    $this->setupThumbnailField();

    $this->useFields(array('name', 'thumbnail'));

    $this->widgetSchema->setNameFormat('collectible_upload[%s]');
    $this->widgetSchema->setFormFormatterName('Bootstrap');
  }

  protected function setupThumbnailField()
  {
    $this->widgetSchema['thumbnail'] = new sfWidgetFormInputFile(
      array('label' => 'Photo')
    );
    $this->validatorSchema['thumbnail'] = new cqValidatorFile(array(
      'mime_types' => 'cq_supported_images', 'required' => true
    ));

    $this->getWidgetSchema()->setHelp(
      'thumbnail', 'Choose a photo which represents your item.'
    );
  }









//  public function configure()
//  {
//    $this->setupCollectorCollectionsField();
//    $this->setupContentCategoryField();
//    $this->useFields(array('collection_collectible_list', 'content_category'));
//
////    $this->validatorSchema->setPostValidator(
////      new sfValidatorCallback(array('callback' => array($this, 'validatePhoto')))
////    );
//
//    $this->getWidgetSchema()->setFormFormatterName('Bootstrap');
//  }









  protected function setupCollectorCollectionsField()
  {
    /** @var $collectible Collectible */
    $collectible = $this->getObject();

    /** @var $collector Collector */
    $collector = $this->getOption('collector', $collectible->getCollector());

    $criteria = new Criteria();
    $criteria->add(CollectorCollectionPeer::COLLECTOR_ID, $collector->getId());
    $criteria->addAscendingOrderByColumn(CollectorCollectionPeer::NAME);

    $this->widgetSchema['collection_collectible_list'] = new sfWidgetFormPropelChoice(
      array(
        'label' => 'Collection(s)',
        'model' => 'CollectorCollection', 'criteria' => $criteria,
        'add_empty' => true, 'multiple' => true
      ),
      array(
        'data-placeholder' => 'Please, choose at least one Collection',
        'class' => 'input-xlarge chzn-select js-hide',
        'style' => 'width: 414px;',
        'required' => 'required'
      )
    );
    $this->validatorSchema['collection_collectible_list'] = new sfValidatorPropelChoice(array(
      'model' => 'CollectorCollection', 'criteria' => $criteria,
      'multiple' => true, 'required' => true
    ));
  }

  protected function setupContentCategoryField()
  {
    $category_edit_url = cqContext::getInstance()->getController()->genUrl(array(
      'sf_route' => 'ajax_mycq',
      'section' => 'collectible',
      'page' => 'changeCategory',
      'collectible_id' => $this->getObject()->getId(),
      'wizard' => '1',
    ));

    $this->widgetSchema['content_category'] = new cqWidgetFormPlain(array(
      'label' => 'Category:',
      'content_tag' => 'div',
      'default_html' => '<span>&nbsp;</span>',
      'extra_html' => sprintf(
        '<a class="btn btn-mini open-dialog" href="%s" style="margin-top: 3px;">%s</a>',
        $category_edit_url,
        'click to change'
      ),
    ), array(
      'style' => 'margin-top: 5px;'
    ));
    $this->validatorSchema['content_category'] = new sfValidatorPass();
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    $this->setDefault(
      'content_category',
      $this->getObject()->getContentCategory()
        ? $this->getObject()->getContentCategory()->getPath()
        : 'No category selected'
    );
  }

  public function validatePhoto($validator, $values)
  {
    if (!$this->getObject()->getPrimaryImage())
    {
      throw new sfValidatorError($validator, 'Photo is required');
     }

    return $values;
  }

}
