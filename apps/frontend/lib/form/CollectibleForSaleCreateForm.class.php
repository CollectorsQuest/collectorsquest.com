<?php

class CollectibleForSaleCreateForm extends CollectibleForSaleForm
{
  public function configure()
  {
    parent::configure();

    $this->setupCollectibleForm();
    $this->setupIsReadyField();
    $this->setupPriceField();
    $this->setupConditionField();

    // add a post validator
    $this->validatorSchema->setPostValidator(
      new sfValidatorCallback(array('callback' => array($this, 'validateIsReadyField')))
    );

    $this->useFields(array(
      'collectible',
      'is_ready',
      'price',
      'price_currency',
      'condition',
    ));

    $this->getWidgetSchema()->setFormFormatterName('Bootstrap');
    $this->getWidgetSchema()->setNameFormat('collectible_for_sale[%s]');
  }

  protected function setupCollectibleForm()
  {
    /** @var $collector Collector */
    $collector = cqContext::getInstance()
      ->getUser()
      ->getCollector();

    $criteria = new Criteria();
    $criteria->add(
      CollectorCollectionPeer::COLLECTOR_ID,
      $collector->getId()
    );
    $criteria->addAscendingOrderByColumn(CollectorCollectionPeer::NAME);

    // Get a new Collectible Form
    $collectible = new CollectibleCreateForm();

    $fields = array();

    $q = CollectorCollectionQuery::create()
      ->filterByCollector($collector)
      ->filterByName('% Items for Sale', Criteria::NOT_LIKE);

    if ($q->count() > 0)
    {
      $collectible->widgetSchema['collection_collectible_list'] = new sfWidgetFormPropelChoice(
        array(
          'label' => 'Collection(s)',
          'model' => 'CollectorCollection', 'criteria' => $criteria,
          'add_empty' => 'Create a new Collection', 'multiple' => true
        ),
        array(
          'data-placeholder' => 'Please, choose at least one Collection',
          'class' => 'input-xlarge chzn-select js-hide',
          'required' => 'required'
        )
      );
      $collectible->validatorSchema['collection_collectible_list'] = new cqValidatorPropelChoice(array(
        'model' => 'CollectorCollection', 'criteria' => $criteria,
        'multiple' => true, 'required' => true, 'min' => 1
      ));
      $collectible->getValidator('collection_collectible_list')->setMessage(
        'invalid', 'Please choose at least one existing Collection or create a new one!'
      );

      $fields[] = 'collection_collectible_list';

      unset(
        $collectible->widgetSchema['collection_id'],
        $collectible->validatorSchema['collection_id']
      );
    }
    else
    {
      $q = CollectorCollectionQuery::create()
        ->filterByCollector($collector);

      if (!$collection = $q->findOne())
      {
        $name = sprintf(
          '%s Items for Sale',
          $collector->getDisplayName() .
          (in_array(substr(strtolower($collector->getDisplayName()), -1, 1), array('s', 'z')) ? "'" : "'s")
        );
        $collection = $q->findOneOrCreate();
        $collection->setName($name);
        $collection->save();
      }

      $collectible->widgetSchema['collection_id'] = new sfWidgetFormInputHidden();
      $collectible->validatorSchema['collection_id'] = new sfValidatorChoice(array(
        'choices' => array($collection->getId()), 'required' => true
      ));
      $collectible->setDefault('collection_id', $collection->getId());

      $fields[] = 'collection_id';

      unset(
        $collectible->widgetSchema['collection_collectible_list'],
        $collectible->validatorSchema['collection_collectible_list']
      );
    }

    $collectible->getWidget('name')->setLabel('Name');

    $fields = array_merge($fields, array(
      'name',
      'tags'
    ));
    $collectible->useFields($fields);

    $this->embedForm('collectible', $collectible);
  }

  public function updateObject($values = null)
  {
    $values['collectible']['collector_id'] = $values['collector_id'];

    /** @var $embedded_form CollectibleCreateForm */
    $embedded_form = $this->getEmbeddedForm('collectible');

    /** @var $collectible Collectible */
    $collectible = $embedded_form->updateObject($values['collectible']);
    $collectible->save();

    if (isset($values['collectible']['collection_collectible_list']))
    {
      $_values = (array) $values['collectible']['collection_collectible_list'];
    }
    else
    {
      $_values = (array) $values['collectible']['collection_id'];
    }

    if (is_array($_values))
    {
      foreach ($_values as $_value)
      {
        $obj = new CollectionCollectible();
        $obj->setCollectibleId($collectible->getId());
        $obj->setCollectionId((integer) $_value);
        $obj->save();
      }
    }

    if (!empty($values['is_ready']))
    {
      /** @var $collectible_for_sale CollectibleForSale */
      $collectible_for_sale = parent::updateObject($values);
      $collectible_for_sale->setCollectible($collectible);
      $collectible_for_sale->setQuantity(1);
      $collectible_for_sale->save();
    }
    else
    {
      $collectible_for_sale = new CollectibleForSale();
      $collectible_for_sale->setCollectible($collectible);
      $collectible_for_sale->setPriceAmount(0);
      $collectible_for_sale->setPriceCurrency('USD');
      $collectible_for_sale->setIsReady(false);
      $collectible_for_sale->setCondition(null);
      $collectible_for_sale->setQuantity(1);
      $collectible_for_sale->save();
    }

    return $collectible_for_sale;
  }

}
