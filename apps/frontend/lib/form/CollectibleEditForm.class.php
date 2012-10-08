<?php

class CollectibleEditForm extends BaseCollectibleForm
{
  public function configure()
  {
    /** @var $collectible Collectible */
    $collectible = $this->getObject();

    /** @var $collector Collector */
    $collector = $this->getOption('collector', $collectible->getCollector());

    $this->widgetSchema['name']->setLabel('Item Name');
    $this->widgetSchema['name']->setAttribute('class', 'input-xlarge');
    $this->widgetSchema['name']->setAttribute('required', 'required');
    $this->widgetSchema['description']->setAttribute('class', 'input-xlarge js-invisible');
    $this->widgetSchema['description']->setAttribute('required', 'required');

    $this->setupCollectorCollectionsField();
    $this->setupThumbnailField();
    $this->setupTagsField();
    $this->setupReturnToField();

    $this->validatorSchema['name'] = new cqValidatorName(
      array('required' => true),
      array('invalid' => 'You need to use more descriptive name for your item
                          (is it the camera auto generated name?)')
    );
    $this->validatorSchema->setPostValidator(new sfValidatorPass());

    // Define which fields to use from the base form
    $this->useFields(array(
      'collection_collectible_list',
      'name',
      'thumbnail',
      'is_alt_view',
      'description',
      'tags',
      'return_to'
    ));

    // We do not want to show the thumbnail field
    // if there is primary image for the Collectible
    if ($collectible->getPrimaryImage())
    {
      $this->offsetUnset('thumbnail');
      $this->offsetUnset('is_alt_view');
    }

    if ($collector->getIsSeller())
    {
      $collectibleForSale = $this->getObject()->getCollectibleForSale();

      if (!$collectibleForSale)
      {
        $collectibleForSale = new CollectibleForSale();
        $collectibleForSale->setCollectible($this->getObject());
      }

      $for_sale_form = new CollectibleForSaleEditForm($collectibleForSale);
      unset($for_sale_form['collectible_id']);
      $this->embedForm('for_sale', $for_sale_form);
      $this->widgetSchema['for_sale']->setLabel('For Sale');
    }

    $this->getWidgetSchema()->setFormFormatterName('Bootstrap');

    $this->validatorSchema->setOption('allow_extra_fields', true);
    $this->validatorSchema->setOption('filter_extra_fields', true);
  }

  protected function setupTagsField()
  {
    // pretty ugly hack, but in this case this is the only way
    // to keep the field's state between requests...
    $tags = $this->getObject()->getTags();

    $this->widgetSchema['tags'] = new cqWidgetFormInputTags(array(
      'label' => 'Tags',
      'autocompleteURL' => '@ajax_typeahead?section=tags&page=edit',
    ), array(
      'required' => 'required',
    ));

    $this->widgetSchema['tags']->setDefault($tags);
    $this->getWidgetSchema()->setHelp(
      'tags', 'Choose at least three descriptive words
               or phrases, separated by commas'
    );

    $this->validatorSchema['tags'] = new cqValidatorTags(array(), array(
        'required' => 'Please enter tags for your item.',
    ));
  }

  protected function setupThumbnailField()
  {
    $this->widgetSchema['thumbnail'] = new sfWidgetFormInputFile(array(
      'label' => 'Item Photo'
    ));
    $this->validatorSchema['thumbnail'] = new cqValidatorFile(array(
      'mime_types' => 'cq_supported_images', 'required' => false
    ));

    $this->widgetSchema['is_alt_view'] = new sfWidgetFormInputHidden();
    $this->validatorSchema['is_alt_view'] = new sfValidatorBoolean();
    $this->setDefault('is_alt_view', false);

    /**
     * We need to make the Thumbnail field required if
     * the Collectible does not have a Thumbnail yet
     */
    if (!$this->getObject()->getPrimaryImage())
    {
      $this->widgetSchema['thumbnail']->setAttribute('required', 'required');
      $this->validatorSchema['thumbnail']->setOption('required', true);
    }
    else
    {
      $this->widgetSchema['is_alt_view'] = new sfWidgetFormInputCheckbox();
    }
  }

  private function setupCollectorCollectionsField()
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

  protected function setupReturnToField()
  {
    $this->widgetSchema['return_to'] = new sfWidgetFormInputHidden();
    $this->validatorSchema['return_to'] = new sfValidatorPass();
  }

  public function save($con = null)
  {
    /** @var $object Collectible */
    $object = parent::save($con);

    /** @var $values array */
    $values = $this->getValues();

    $object->setDescription($values['description'], 'html');
    $object->setTags($values['tags']);

    if (isset($values['thumbnail']))
    {
      if (isset($values['is_alt_view']) && $values['is_alt_view'] === true)
      {
        $object->addMultimedia($values['thumbnail']);
      }
      else
      {
        $object->setPrimaryImage($values['thumbnail']);
      }
    }

    $object->save();

    return $object;
  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->saveCollectionCollectibleList($con);
  }

  public function updateObject($values = null)
  {
    if (null === $values)
    {
      $values = $this->values;
    }

    $values = $this->processValues($values);

    // If the collectible for sale was not marked as ready before,
    // but is currently being marked as such
    if (
      $this->getObject()->getCollectibleForSale() &&
      !$this->getObject()->getCollectibleForSale()->getIsReady() &&
      isset($values['for_sale']['is_ready']) && $values['for_sale']['is_ready']
    ) {
      try {
        // try to create a transaction credit for this collectible
        PackageTransactionCreditPeer::findActiveOrCreateForCollectible($this->getObject());
      } catch (CollectorHasNoCreditsAvailableException $e) {
        // and if for some reason there were no available credits
        // auto-set is_ready to false
        $values['for_sale']['is_ready'] = false;
      }
    }

    $this->doUpdateObject($values);

    // embedded forms
    $this->updateObjectEmbeddedForms($values);

    return $this->getObject();
  }

  public function saveCollectionCollectibleList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['collection_collectible_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $values = $this->getValue('collection_collectible_list');
    if ($values && is_array($values))
    {
      $c = new Criteria();
      $c->add(CollectionCollectiblePeer::COLLECTIBLE_ID, $this->object->getPrimaryKey());
      $c->add(CollectionCollectiblePeer::COLLECTION_ID, $values, Criteria::NOT_IN);
      CollectionCollectiblePeer::doDelete($c, $con);

      foreach ($values as $value)
      {
        $q = CollectionCollectibleQuery::create()
           ->filterByCollectibleId($this->object->getPrimaryKey())
           ->filterByCollectionId((int) $value);

        $obj = $q->findOneOrCreate();
        $obj->save();
      }
    }
    else
    {
      $c = new Criteria();
      $c->add(CollectionCollectiblePeer::COLLECTIBLE_ID, $this->object->getPrimaryKey());
      CollectionCollectiblePeer::doDelete($c, $con);
    }
  }

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    parent::bind($taintedValues, $taintedFiles);

    /** @var $form BaseForm */
    foreach ($this->embeddedForms as $name => $form)
    {
      $form->bind(
        @$taintedValues[$name],
        isset($taintedFiles[$name]) ? $taintedFiles[$name] : array()
      );
    }
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['collection_collectible_list']))
    {
      $values = array();
      foreach ($this->object->getCollectionCollectibles() as $obj)
      {
        $values[] = $obj->getCollectionId();
      }

      $this->setDefault('collection_collectible_list', $values);
    }
  }
}
