<?php

/**
 * ContentCategory form.
 *
 * @package    CollectorsQuest
 * @subpackage form
 * @author     Collectors
 *
 * @method     ContentCategory getObject()
 */
class ContentCategoryForm extends BaseContentCategoryForm
{
  public function configure()
  {
    $this->setupCollectionCategoryIdField();
    $this->setupParentIdField();
    $this->setupSlugField();

    $this->unsetFields();
  }

  protected function unsetFields()
  {
    unset ($this['tree_left']);
    unset ($this['tree_right']);
    unset ($this['tree_level']);
  }

  protected function setupCollectionCategoryIdField()
  {
    $this->widgetSchema['collection_category_id'] = new cqWidgetFormPropelChoiceByParentId(array(
        'model' => 'CollectionCategory',
        'add_empty' => true,
        'id_to_make_first' => 0,
    ));
  }

  protected function setupParentIdField()
  {
    $this->widgetSchema['parent_id'] = new cqWidgetFormPropelChoiceByNestedSet(array(
        'model' => 'ContentCategory',
        'add_empty' => true, 'chozen' => true
    ));
    $this->validatorSchema['parent_id'] = new sfValidatorPropelChoice(array(
        'model' => 'ContentCategory',
        'query_methods' => array(
            // do not allow selecting yourself as parent
            'filterById' => array(
                $this->isNew() ? null : $this->getObject()->getId(),
                Criteria::NOT_EQUAL),
        )
    ));
  }

  protected function setupSlugField()
  {
    $this->validatorSchema['slug']->setOption('required', false);
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    $parent = $this->getObject()->getParent();
    $this->setDefault('parent_id', $parent ? $parent->getId() : null);
  }

  public function doUpdateObject($values = null)
  {
    parent::doUpdateObject($values);

    // check if we need to reorder the tree
    $parent = ContentCategoryPeer::retrieveByPK($values['parent_id']);

    if ($this->getObject()->getParent() != $parent)
    {
      if ($this->getObject()->isInTree())
      {
        // if the current object is somewhere in the tree
        // we move it to its new parent
        $this->getObject()->moveToLastChildOf($parent);
      }
      else
      {
        // if not in tree, then this is a new object
        // and we insert it as the last child of its parent
        $this->getObject()->insertAsLastChildOf($parent);
      }
    }
  }

}
