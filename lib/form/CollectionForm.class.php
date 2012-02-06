<?php

/**
 * Collection form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Kiril Angov
 * @version    SVN: $Id: sfPropelFormTemplate.php 10377 2008-07-21 07:10:32Z dwhittle $
 */
class CollectionForm extends BaseCollectionForm
{
  public function configure()
  {
    $this->widgetSchema['collection_category_id'] = new sfWidgetFormPropelChoice(array(
      'model' => 'CollectionCategory', 'add_empty' => true,
      'order_by' => array('Name', 'Asc'), 'method' => 'getNameWithParent'
    ));
  }
}
