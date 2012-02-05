<?php

/**
 * CollectionCategory filter form.
 *
 * @package    CollectorsQuest
 * @subpackage filter
 * @author     Kiril Angov
 * @version    SVN: $Id: sfPropelFormFilterTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class CollectionCategoryFormFilter extends BaseCollectionCategoryFormFilter
{
  public function configure()
  {
    $this->widgetSchema['parent'] = new sfWidgetFormPropelChoice(array(
      'model' => 'CollectionCategory', 'add_empty' => true,
      'order_by' => array('Name', 'asc'), 'query_methods' => array('isParent')
    ));

    $this->validatorSchema['parent'] = new sfValidatorPropelChoice(array('required' => false, 'model' => 'CollectionCategory', 'column' => 'id'));
  }
}
