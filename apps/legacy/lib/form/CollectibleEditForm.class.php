<?php

class CollectibleEditForm extends BaseCollectibleForm
{
  public function configure()
  {
    parent::configure();

    /** @var $collector Collector */
    $collector = $this->getOption('collector', $this->getObject()->getCollector());

    $this->getWidgetSchema()->setFormFormatterName('legacy');

    $this->validatorSchema->setOption('allow_extra_fields', true);
    $this->validatorSchema->setOption('filter_extra_fields', true);

    $criteria = new Criteria();
    $criteria->add(CollectionPeer::COLLECTOR_ID, $collector->getId());
    $criteria->addAscendingOrderByColumn(CollectionPeer::NAME);

    $this->widgetSchema['collection_id'] = new sfWidgetFormPropelChoice(array(
      'model' => 'Collection', 'criteria' => $criteria, 'add_empty' => true
    ));

    $this->widgetSchema['thumbnail'] = new sfWidgetFormInputFile();
    $this->validatorSchema['thumbnail'] = new sfValidatorFile(array('required' => false));

    $this->widgetSchema['tags'] = new sfWidgetFormInput();
    $this->validatorSchema['tags'] = new sfValidatorPass();

    $this->validatorSchema->setPostValidator(new sfValidatorPass());

    unset($this->widgetSchema['graph_id'], $this->validatorSchema['graph_id']);
    unset($this->widgetSchema['collector_id'], $this->validatorSchema['collector_id']);
    unset($this->widgetSchema['slug'], $this->validatorSchema['slug']);

    unset($this->widgetSchema['position'], $this->widgetSchema['score']);
    unset($this->validatorSchema['position'], $this->validatorSchema['score']);

    if ($collector && $collector->getUserType() == 'Seller')
    {
      $collectibleForSale = $this->getObject()->getForSaleInformation();

      if (!$collectibleForSale)
      {
        $collectibleForSale = new CollectibleForSale();
        $collectibleForSale->setCollectible($this->getObject());
      }

      $embedForm = new CollectibleForSaleEditForm($collectibleForSale);

      $this->embedForm('for_sale', $embedForm);
    }
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
    $values = $this->getValues();

    $object->setDescription($values['description'], 'html');

    if ($this->getValue('thumbnail'))
    {
      $collection = $object->getCollection();
      if (!$collection->hasThumbnail())
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
