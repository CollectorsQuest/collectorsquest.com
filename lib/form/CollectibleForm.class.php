<?php

/**
 * Collectible form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Kiril Angov
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class CollectibleForm extends BaseCollectibleForm
{
  public function configure()
  {
    $this->setupTagsField();
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

    if (isset($values['description']))
    {
      $object->setDescription($values['description'], 'html');
    }

    if (isset($values['tags']))
    {
      $object->setTags($values['tags']);
    }

    $object->save();

    return $object;
  }

}
