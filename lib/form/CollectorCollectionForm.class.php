<?php

/**
 * CollectorCollection form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Collectors
 */
class CollectorCollectionForm extends BaseCollectorCollectionForm
{
  public function configure()
  {
    $this->setupTagsField();
    $this->widgetSchema['description']->setAttributes(array('rows'=>10, 'cols'=>50, 'style'=>'width: 300px;'));
  }

  protected function setupTagsField()
  {
    // pretty ugly hack, but in this case this is the only way
    // to keep the field's state between requests...
    $tags = $this->getObject()->getTags();

    $this->widgetSchema['tags'] = new cqWidgetFormMultipleInputText(array(
      'label' => 'Tags'
    ), array(
      'name' => 'collector_collection[tags][]',
      'class' => 'tag'
    ));

    $this->widgetSchema['tags']->setDefault($tags);
    $this->validatorSchema['tags'] = new sfValidatorCallback(
      array('required' => false, 'callback' => array($this, 'validateTagsField'))
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

}
