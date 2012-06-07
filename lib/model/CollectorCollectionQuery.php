<?php

require 'lib/model/om/BaseCollectorCollectionQuery.php';

class CollectorCollectionQuery extends BaseCollectorCollectionQuery
{
  /**
   * @param ContentCategory $content_category
   * @param string $comparison
   *
   * @return CollectibleForSaleQuery
   */
  public function filterByContentCategoryWithDescendants($content_category, $comparison = null)
  {
    return $this
      ->useCollectionQuery()
        ->filterByContentCategory(
          ContentCategoryQuery::create()
            ->descendantsOfObjectIncluded($content_category)->find(),
          $comparison
        )
      ->endUse();
  }

  /**
   * @param  array   $tags
   * @param  string  $comparison
   *
   * @return CollectorCollectionQuery
   */
  public function filterByTags($tags, $comparison = null)
  {
    $tags = !is_array($tags) ? explode(',', (string) $tags) : $tags;
    $tags = array_map(array('Utf8', 'slugify'), $tags);

    $where = sprintf("
        CollectorCollection.Id IN (
          SELECT tagging.taggable_id
            FROM tagging RIGHT JOIN tag ON (tag.id = tagging.tag_id AND tag.slug %s ('%s'))
           WHERE taggable_model = 'CollectorCollection'
        )
      ",
      $comparison === Criteria::NOT_IN ? 'NOT IN' : 'IN',
      implode("','", $tags)
    );

    return $this->where($where);
  }

  public function haveThumbnail()
  {
    // @todo: we need implementation
    return $this;
  }

  public function search($v)
  {
    return $this
      ->useCollectionQuery()
        ->filterByName('%'. $v .'%', Criteria::LIKE)
        ->_or()
        ->filterByDescription('%'. $v .'%', Criteria::LIKE)
      ->endUse();
  }
}
