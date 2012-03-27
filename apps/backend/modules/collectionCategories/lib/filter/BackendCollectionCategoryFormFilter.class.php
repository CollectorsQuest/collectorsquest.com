<?php

/**
 * CollectionCategory filter form.
 *
 * @package    CollectorsQuest
 * @subpackage filter
 * @author     Kiril Angov
 * @version    SVN: $Id: sfPropelFormFilterTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BackendCollectionCategoryFormFilter extends BaseCollectionCategoryFormFilter
{
  public function configure()
  {
    $this->setupParentIdField();
  }

  public function setupParentIdField()
  {
    $this->widgetSchema['parent_id'] = new bsWidgetFormInputTypeAhead(array(
      'source' => $this->getOption('url_parent', sfContext::getInstance()->getController()->genUrl('collectionCategories/parent')),
    ));

    $this->validatorSchema['parent_id'] = new sfValidatorPropelChoice(array('required' => false, 'model' => 'CollectionCategory', 'column' => 'id'));
  }
  /**
   * @param CollectionCategoryQuery $criteria
   * @param string $field
   * @param string|null $values
   *
   * @return ModelCriteria
   */
  public function addParentIdColumnCriteria($criteria, $field, $values = null)
  {
    $parent = CollectionCategoryQuery::create()->findOneByName($values);
    $parentId = $parent instanceof CollectionCategory ? $parent->getId() : null;

    return $criteria->filterByParentId($parentId);

    //Next disabled until schema is fixed
    if (!is_null($values))
    {
      $criteria->useCollectionCategoryRelatedByParentIdQuery()
        ->filterByName($values)
        ->endUse();
    }

    return $criteria;
  }
}
