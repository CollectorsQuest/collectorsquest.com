<?php

/**
 * ContentCategory form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Collectors
 */
class ContentCategoryForm extends BaseContentCategoryForm
{
  public function configure()
  {
    $this->widgetSchema['collection_category_id'] = new cqWidgetFormPropelChoiceByParentId(array(
        'model' => 'CollectionCategory',
        'order_by' => array('Name', 'asc'),
        'add_empty' => true,
        'id_to_make_first' => 0,
    ));

    $this->unsetFields();
  }

  protected function unsetFields()
  {
    unset ($this['tree_left']);
    unset ($this['tree_right']);
    unset ($this['tree_level']);
  }

}