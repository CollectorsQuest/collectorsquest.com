<?php

require 'lib/model/om/BaseCollectibleQuery.php';

class CollectibleQuery extends BaseCollectibleQuery
{
  public function filterByCollectionId($id, $comparison = null)
  {
    $this->addUsingAlias(CollectionCollectiblePeer::COLLECTION_ID, $id, $comparison);

    return $this;
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

  /**
   * @param  string  $v
   * @return CollectibleQuery
   */
  public function search($v)
  {
    return $this
      ->filterByName('%'. $v .'%', Criteria::LIKE)
      ->_or()
      ->filterByDescription('%'. $v .'%', Criteria::LIKE);
  }
}
