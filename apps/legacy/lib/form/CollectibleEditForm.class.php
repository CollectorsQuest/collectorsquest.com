<?php

class CollectibleEditForm extends BaseCollectibleForm
{
  public function configure()
  {
    parent::configure();

    $this->validatorSchema->setOption('allow_extra_fields', true);
    $this->validatorSchema->setOption('filter_extra_fields', true);

    $this->getWidgetSchema()->setFormFormatterName('legacy');

    $this->validatorSchema->setPostValidator(new sfValidatorPass());

    $this->widgetSchema['thumbnail'] = new sfWidgetFormInputFile();
    $this->validatorSchema['thumbnail'] = new sfValidatorFile(array('required' => false));

    $this->widgetSchema['tags'] = new sfWidgetFormInput();
    $this->validatorSchema['tags'] = new sfValidatorPass();

    unset($this->widgetSchema['graph_id'], $this->validatorSchema['graph_id']);
    unset($this->widgetSchema['collector_id'], $this->validatorSchema['collector_id']);
    unset($this->widgetSchema['collection_id'], $this->validatorSchema['collection_id']);
    unset($this->widgetSchema['slug'], $this->validatorSchema['slug']);

    unset($this->widgetSchema['position'], $this->widgetSchema['score']);
    unset($this->validatorSchema['position'], $this->validatorSchema['score']);
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
