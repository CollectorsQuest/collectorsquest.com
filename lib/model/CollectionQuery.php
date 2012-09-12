<?php

require 'lib/model/om/BaseCollectionQuery.php';

class CollectionQuery extends BaseCollectionQuery
{
  /**
   * @param  array|PropelObjectCollection|ContentCategory  $content_category
   * @param  string  $comparison
   *
   * @return CollectorCollectionQuery
   */
  public function filterByContentCategoryWithDescendants($content_category, $comparison = null)
  {
    /** @var $q ContentCategoryQuery */
    $q = ContentCategoryQuery::create();

    if (is_array($content_category) || $content_category instanceof PropelCollection)
    {
      foreach ($content_category as $category)
      {
        if ($category instanceof ContentCategory)
        {
          $q->_or()
            ->descendantsOfObjectIncluded($category);
        }
      }
    }
    else if ($content_category instanceof ContentCategory)
    {
      $q->descendantsOfObjectIncluded($content_category);
    }

    if ($q->hasWhereClause())
    {
      return $this->filterByContentCategory($q->find(), $comparison);
    }
    else
    {
      return $this->filterByContentCategory($content_category, $comparison);
    }
  }

  /**
   * @return CollectorCollectionQuery
   */
  public function hasCollectibles()
  {
    return $this
      ->filterByNumItems(0, Criteria::GREATER_THAN);
  }

  /**
   * @param      string  $v
   * @return     CollectionQuery
   */
  public function search($v)
  {
    return $this
      ->filterByName('%'. trim($v) .'%', Criteria::LIKE)
      ->_or()
      ->filterByDescription('%'. trim($v) .'%', Criteria::LIKE);
  }
}
