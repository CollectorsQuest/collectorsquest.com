<?php

/**
 * ContentCategory filter form.
 *
 * @package    CollectorsQuest
 * @subpackage filter
 * @author     Collectors
 */
class ContentCategoryFormFilter extends BaseContentCategoryFormFilter
{
  public function configure()
  {
    $this->setWidget('id', new sfWidgetFormFilterInput(array('with_empty' => false)));
    $this->setValidator('id', new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))));

    $this->widgetSchema['ancestor_id'] = new cqWidgetFormPropelChoiceByNestedSet(array(
        'model' => 'ContentCategory',
        'add_empty' => true, 'chozen' => true
    ));
    $this->validatorSchema['ancestor_id'] = new sfValidatorPropelChoice(array(
        'required' => false,
        'model' => 'ContentCategory',
        'column' => 'id',
    ));

    $this->setWidget('level', new sfWidgetFormSelectMany(array('choices' => array(1 => '1', '2', '3', '4', '5', '6'))));
    $this->setValidator('level', new sfValidatorChoice(array(
      'choices' => array(1 => '1', '2', '3', '4', '5', '6'),
      'multiple' => true, 'required' => false
    )));

    $this->setWidget('with_collections', new sfWidgetFormInputCheckbox());
    $this->setValidator('with_collections', new sfValidatorBoolean(array('required' => false)));

    $this->unsetFields();
  }

  protected function unsetFields()
  {
    unset ($this['tree_left']);
    unset ($this['tree_right']);
    unset ($this['tree_level']);
  }

  public function addAncestorIdColumnCriteria(ContentCategoryQuery $q, $field, $value)
  {
    if ($ancestor = ContentCategoryPeer::retrieveByPK($value))
    {
      $q->descendantsOf($ancestor);
    }
  }

  public function addWithCollectionsColumnCriteria(ContentCategoryQuery $q, $field, $value)
  {
    if ($value === true)
    {
      $q->withCollections();
    }
  }
}
