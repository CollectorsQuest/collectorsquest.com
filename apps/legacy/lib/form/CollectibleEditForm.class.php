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

    $this->widgetSchema['tags'] = new sfWidgetFormInput();
    $this->validatorSchema['tags'] = new sfValidatorPass();

    $this->validatorSchema->setPostValidator(new sfValidatorPass());

    if ($collector->getIsSeller())
    {
      $collectibleForSale = $this->getObject()->getForSaleInformation();

      if (!$collectibleForSale)
      {
        $collectibleForSale = new CollectibleForSale();
        $collectibleForSale->setCollectible($this->getObject());
      }

      $this->embedForm('for_sale', new CollectibleForSaleEditForm($collectibleForSale));
    }

    // Define which fields to use from the base form
    $this->useFields(array(
      'collection_collectible_list', 'name', 'description', 'thumbnail', 'tags'
    ));

    $this->getWidgetSchema()->setFormFormatterName('legacy');

    $this->validatorSchema->setOption('allow_extra_fields', true);
    $this->validatorSchema->setOption('filter_extra_fields', true);
  }

  public function updateDescriptionColumn($value)
  {
    $this->getObject()->setDescription($value, 'html');
  }

  protected function saveFile($field, $filename = null, sfValidatedFile $file = null)
  {
    parent::saveFile($field, $filename, $file);

    if ('thumbnail' == $field)
    {
      $this->getObject()->addMultimedia($filename, true);
    }
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

      $object->addMultimedia($this->getValue('thumbnail'), true);
    }

    if ($values['tags'])
    {
      $object->setTags($values['tags']);
    }

    $object->save();

    return $object;
  }

}
