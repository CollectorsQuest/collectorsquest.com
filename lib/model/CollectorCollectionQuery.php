<?php

require 'lib/model/om/BaseCollectorCollectionQuery.php';

class CollectorCollectionQuery extends BaseCollectorCollectionQuery
{

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

  /**
   * @param  array   $tags
   * @param  string  $namespace
   * @param  string  $key
   * @param  string  $comparison
   *
   * @return CollectorCollectionQuery
   */
  public function filterByMachineTags($tags, $namespace, $key = 'all', $comparison = Criteria::IN)
  {
    $tags = !is_array($tags) ? explode(',', (string) $tags) : $tags;
    $tags = array_map('addslashes', $tags);

    $where = sprintf("
        Collectible.Id IN (
          SELECT tagging.taggable_id
            FROM tagging RIGHT JOIN tag ON (tag.id = tagging.tag_id AND tag.is_triple = 1)
           WHERE taggable_model = 'CollectorCollection'
             AND tag.triple_value %s ('%s')
             AND tag.triple_namespace = '%s'
             AND tag.triple_key = '%s'
        )
      ",
      $comparison === Criteria::NOT_IN ? 'NOT IN' : 'IN',
      implode("','", $tags), $namespace, $key
    );

    return $this->where($where);
  }

  /**
   * @return CollectorCollectionQuery
   */
  public function hasThumbnail()
  {
    // @todo: we need implementation
    return $this;
  }

}
