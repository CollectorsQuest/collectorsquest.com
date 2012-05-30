<?php

class CollectibleEditForm extends BaseCollectibleForm
{
  public function configure()
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
        'class' => 'input-xlarge chzn-select',
        'style' => 'width: 410px;',
        'required' => 'required')
    );
    $this->validatorSchema['collection_collectible_list'] = new sfValidatorPropelChoice(array(
      'multiple' => true, 'model' => 'Collection', 'required' => true)
    );

    $this->widgetSchema['name']->setAttribute('class', 'input-xlarge');
    $this->widgetSchema['name']->setAttribute('required', 'required');
    $this->widgetSchema['description']->setAttribute('class', 'input-xlarge');
    $this->widgetSchema['description']->setAttribute('required', 'required');

    $this->setupTagsField();

    $this->validatorSchema->setPostValidator(new sfValidatorPass());

    // Define which fields to use from the base form
    $this->useFields(array(
      'collection_collectible_list', 'name', 'description', 'tags'
    ));

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

    $this->widgetSchema['tags'] = new cqWidgetFormMultipleInputText(array(
      'label' => 'Tags'
    ), array(
      'name' => 'collectible[tags][]',
      'required' => 'required',
      'class' => 'tag'
    ));

    $this->widgetSchema['tags']->setDefault($tags);
    $this->getWidgetSchema()->setHelp(
      'tags', 'Choose at least three descriptive words or
               phrases for your collectible, separated by commas'
    );

    $this->validatorSchema['tags'] = new sfValidatorCallback(
      array('required' => true, 'callback' => array($this, 'validateTagsField'))
    );
  }

  public function validateTagsField($validator, $values)
  {
    $values = (array) $values;

    if (empty($values)) {
      throw new sfValidatorError($validator, 'required');
    }
    else {
      return $values;
    }
  }

  public function updateDescriptionColumn($value)
  {
    $this->getObject()->setDescription($value, 'html');
  }

  public function save($con = null)
  {
    /** @var $object Collectible */
    $object = parent::save($con);

    /** @var $values array */
    $values = $this->getValues();

    $object->setDescription($values['description'], 'html');
    $object->setTags($values['tags']);

    $object->save();

    return $object;
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

}
