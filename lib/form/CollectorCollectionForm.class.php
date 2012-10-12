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
    $this->setupInternalTagsField();
    $this->setupContentCategoryIdField();
    $this->widgetSchema['description']->setAttributes(array(
        'rows' => 10, 'cols' => 50,
        'style' => 'width: 300px;'
    ));
  }

  protected function setupContentCategoryIdField()
  {
    $this->widgetSchema['content_category_id'] = new cqWidgetFormPropelChoiceByNestedSet(array(
      'model' => 'ContentCategory', 'chozen' => true
    ));
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
      'class' => 'tag'
    ));

    $this->widgetSchema['tags']->setDefault($tags);
    $this->validatorSchema['tags'] = new cqValidatorTags(array(
        'required' => false,
    ));
  }

  protected function setupInternalTagsField()
  {
    // pretty ugly hack, but in this case this is the only way
    // to keep the field's state between requests...
    $tags = $this->getObject()->getInternalTags();

    $this->widgetSchema['internal_tags'] = new cqWidgetFormInputTags(array(
      'label' => 'Internal Tags',
      'autocompleteURL' => '@ajax_typeahead?section=tags&page=edit',
    ), array(
      'class' => 'tag'
    ));

    $this->widgetSchema['internal_tags']->setDefault($tags);
    $this->validatorSchema['internal_tags'] = new cqValidatorTags(array(
      'required' => false,
    ));
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
    $object->setInternalTags($values['internal_tags']);
    $object->save();

    return $object;
  }

}
