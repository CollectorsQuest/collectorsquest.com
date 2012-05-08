<?php

require 'lib/model/om/BaseCollectorCollectionQuery.php';

class CollectorCollectionQuery extends BaseCollectorCollectionQuery
{
  /**
   * @param ContentCategory $content_category
   * @param string $comparison
   *
   * @return CollectibleForSaleQuery
   * @throws PropelException
   */
  public function filterByContentCategoryWithDescendants($content_category, $comparison = null)
  {
    /** @var $content_category ContentCategory */
    if ($content_category instanceof ContentCategory)
    {
      $q = $this
        ->joinCollection()
        ->useCollectionQuery();

      if ($comparison === Criteria::NOT_EQUAL || $comparison === Criteria::NOT_IN)
      {
        $q
          ->addUsingAlias(CollectionPeer::CONTENT_CATEGORY_ID, $content_category->getId(), Criteria::NOT_EQUAL)
          ->addUsingAlias(CollectionPeer::CONTENT_CATEGORY_ID, $content_category->getChildren()->toKeyValue('PrimaryKey', 'Id'), Criteria::NOT_IN);
      }
      else
      {
        $q
          ->addUsingAlias(CollectionPeer::CONTENT_CATEGORY_ID, $content_category->getId(), Criteria::EQUAL)
          ->_or()
          ->addUsingAlias(CollectionPeer::CONTENT_CATEGORY_ID, $content_category->getDescendants()->toKeyValue('PrimaryKey', 'Id'), Criteria::IN);
      }

      $q->endUse();
    }
    else
    {
      throw new PropelException('filterByContentCategory() only accepts arguments of type ContentCategory');
    }

    return $this;
  }
}
