<?php

class CollectibleForSaleCreateForm extends CollectibleForSaleForm
{
  public function configure()
  {
    parent::configure();

    $this->setupCollectibleForm();
    $this->setupPriceField();
    $this->setupConditionField();

    // add a post validator
    $this->validatorSchema->setPostValidator(
      new sfValidatorCallback(array('callback' => array($this, 'validatePriceField')))
    );

    $this->useFields(array(
      'collectible',
      'is_ready',
      'price',
      'price_currency',
      'condition'
    ));

    $this->getWidgetSchema()->setFormFormatterName('Bootstrap');
    $this->getWidgetSchema()->setNameFormat('collectible_for_sale[%s]');
  }

  protected function setupCollectibleForm()
  {
    $criteria = new Criteria();
    $criteria->add(
      CollectorCollectionPeer::COLLECTOR_ID,
      sfContext::getInstance()->getUser()->getId()
    );
    $criteria->addAscendingOrderByColumn(CollectorCollectionPeer::NAME);

    $collectible = new CollectibleCreateForm();
    $collectible->widgetSchema['collection_collectible_list'] = new sfWidgetFormPropelChoice(
      array(
        'label' => 'Collection(s)',
        'model' => 'CollectorCollection', 'criteria' => $criteria,
        'add_empty' => true, 'multiple' => true
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

    unset(
    $collectible->widgetSchema['collection_id'],
    $collectible->validatorSchema['collection_id']
    );

    $collectible->useFields(array(
      'collection_collectible_list',
      'name',
      'tags',
      'thumbnail'
    ));

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

    $_values = $values['collectible']['collection_collectible_list'];
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

    /** @var $collectible_for_sale CollectibleForSale */
    $collectible_for_sale = parent::updateObject($values);
    $collectible_for_sale->setCollectible($collectible);
    $collectible_for_sale->save();

    return $collectible_for_sale;
  }

}
