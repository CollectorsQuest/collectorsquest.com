<?php

require 'lib/model/om/BaseCollectibleQuery.php';

class CollectibleQuery extends BaseCollectibleQuery
{
  public function filterByCollectionId($id, $comparison = null)
  {
    return $this
      ->joinCollectionCollectible()
      ->addUsingAlias(CollectionCollectiblePeer::COLLECTION_ID, $id, $comparison);
  }

  /**
   * @param  array   $tags
   * @param  string  $comparison
   *
   * @return CollectibleQuery
   */
  public function filterByTags($tags, $comparison = null)
  {
    $tags = !is_array($tags) ? explode(',', (string) $tags) : $tags;
    $tags = array_map(array('Utf8', 'slugify'), $tags);

    $where = sprintf("
        Collectible.Id IN (
          SELECT tagging.taggable_id
            FROM tagging RIGHT JOIN tag ON (tag.id = tagging.tag_id AND tag.slug %s ('%s'))
           WHERE taggable_model = 'Collectible'
        )
      ",
      $comparison === Criteria::NOT_IN ? 'NOT IN' : 'IN',
      implode("','", $tags)
    );

    return $this->where($where);
  }

  public function hasThumbnail()
  {
    // @todo: to implement
    return $this;
  }

  /**
   * @return CollectibleQuery
   */
  public function isForSale()
  {
    return $this
      ->useCollectibleForSaleQuery()
        ->isForSale()
      ->endUse();
  }

  /**
   * @return CollectibleQuery
   */
  public function isPartOfCollection()
  {
    return $this
      ->join('CollectionCollectible', Criteria::RIGHT_JOIN)
      ->groupBy('Collectible.Id');
  }

  /**
   * @return CollectibleQuery
   */
  public function isComplete()
  {
    return $this->filterByIsPublic(true, Criteria::EQUAL);
  }

  /**
   * @return CollectibleQuery
   */
  public function isIncomplete()
  {
    return $this->filterByIsPublic(false, Criteria::EQUAL);
  }

  /**
   * @param  string  $v
   * @return CollectibleQuery
   */
  public function search($v)
  {
    return $this
      ->filterByName('%'. trim($v) .'%', Criteria::LIKE)
      ->_or()
      ->filterByDescription('%'. trim($v) .'%', Criteria::LIKE);
  }
}
