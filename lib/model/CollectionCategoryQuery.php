<?php

/**
 * Skeleton subclass for performing query and update operations on the 'collection_category' table.
 *
 * @package    propel.generator.lib.model
 */
class CollectionCategoryQuery extends BaseCollectionCategoryQuery
{
  /**
   * @param string | CollectionCatregory $category
   * @param string $criteria
   *
   * @return CollectionCategoryQuery
   */
  public function filterByParent($category = null, $criteria = Criteria::EQUAL)
  {
    if (is_numeric($category))
    {
      $category = CollectionCategoryQuery::create()->findOneById($category);
    }

    $parent_id = ($category instanceof CollectionCategory) ? $category->getId() : 0;
    $this->filterByParentId($parent_id, $criteria);

    return $this;
  }

	public function isParent()
  {
    $this->filterByParentId(0, Criteria::EQUAL);

    return $this;
  }

  public function isNotParent()
  {
    $this->filterByParentId(0, Criteria::GREATER_THAN);

    return $this;
  }
}
