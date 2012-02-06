<?php

/**
 * CollectionCategory form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Kiril Angov
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class CollectionCategoryForm extends BaseCollectionCategoryForm
{
  public function configure()
  {
    $this->widgetSchema['parent'] = new sfWidgetFormPropelChoice(array(
      'model' => 'CollectionCategory', 'add_empty' => true,
      'order_by' => array('Name', 'asc'), 'query_methods' => array('isParent')
    ));
    $this->validatorSchema['parent'] = new sfValidatorPropelChoice(array('required' => false, 'model' => 'CollectionCategory', 'column' => 'id'));
    $this->setDefault('parent', $this->getObject()->getParentId());

    $this->widgetSchema['tags'] = new sfWidgetFormInput();
    $this->validatorSchema['tags'] = new sfValidatorString(array('required' => true));
    $this->setDefault('tags', $this->getObject()->getTagsString());
  }

  public function save($con = null)
  {
    $object = parent::save($con);

    $object->setParentId((int) $this->getValue('parent'));
    $object->setTags((string) $this->getValue('tags'));
    $object->save();

    return $object;
  }
}
