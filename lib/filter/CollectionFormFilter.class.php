<?php

/**
 * Collection filter form.
 *
 * @package    CollectorsQuest
 * @subpackage filter
 * @author     Kiril Angov
 * @version    SVN: $Id: sfPropelFormFilterTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class CollectionFormFilter extends BaseCollectionFormFilter
{
  public function configure()
  {
    $this->widgetSchema['collection_category_id'] = new sfWidgetFormPropelChoice(array(
      'model' => 'CollectionCategory', 'add_empty' => true,
      'order_by' => array('Name', 'Asc'), 'method' => 'getNameWithParent'
    ));
  }
}
