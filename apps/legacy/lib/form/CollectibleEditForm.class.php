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

    $this->widgetSchema['collection_collectible_list'] = new sfWidgetFormPropelChoice(array(
      'model' => 'CollectorCollection', 'criteria' => $criteria,
      'add_empty' => true, 'multiple' => true
    ));

    $this->widgetSchema['thumbnail'] = new sfWidgetFormInputFile();
    $this->validatorSchema['thumbnail'] = new sfValidatorFile(array('required' => false));

    $this->setupTagsField();

    $this->validatorSchema->setPostValidator(new sfValidatorPass());

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

    // Define which fields to use from the base form
    $this->useFields(array(
      'collection_collectible_list', 'name', 'description', 'thumbnail', 'tags'
    ));

    $this->getWidgetSchema()->setFormFormatterName('legacy');

    $this->validatorSchema->setOption('allow_extra_fields', true);
    $this->validatorSchema->setOption('filter_extra_fields', true);
  }

  protected function setupTagsField()
  {
    // pretty ugly hack, but in this case this is the only way
    // to keep the field's state between requests...

    $tags = $this->getObject()->getTags();
    if (sfContext::hasInstance())
    {
      $request = sfContext::getInstance()->getRequest();
      if (( $values = $request->getParameter($this->getName()) ))
      {
        if (isset($values['tags']))
        {
          $tags = $values['tags'];
        }
      }
    }

    $this->widgetSchema['tags'] = new cqWidgetFormChoice(array(
        'choices' => count($tags) ? array_combine($tags, $tags) : $tags,
        'multiple' => true,
    ));

    $this->validatorSchema['tags'] = new sfValidatorPass();
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

    if ($this->getValue('thumbnail'))
    {
      $collection = $object->getCollection();
      if ($collection && !$collection->hasThumbnail())
      {
        $collection->setThumbnail($this->getValue('thumbnail')->getTempName());
        $collection->save();
      }

      $object->setThumbnail($this->getValue('thumbnail'));
    }

    if ($values['tags'])
    {
      $object->setTags($values['tags']);
    }

    $object->save();

    return $object;
  }

}
