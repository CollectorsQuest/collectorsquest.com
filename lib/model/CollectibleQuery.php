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
          SELECT tagging.taggable_id from (
            SELECT tag.id from tag WHERE tag.is_triple = 0 AND tag.slug %s ('%s')
          ) res INNER JOIN tagging ON (res.id = tag_id AND taggable_model = 'Collectible')
        )
      ",
      $comparison === Criteria::NOT_IN ? 'NOT IN' : 'IN',
      implode("','", $tags)
    );

    return $this->where($where);
  }

  /**
   * @param  array   $tags
   * @param  string  $comparison
   *
   * @return CollectibleQuery
   */
  public function orderByTags($tags, $comparison = null)
  {
    $tags = !is_array($tags) ? explode(',', (string) $tags) : $tags;
    $tags = array_map(array('Utf8', 'slugify'), $tags);

    $where = sprintf("
        FIELD(
          collectible.ID,
          (
            SELECT
              GROUP_CONCAT(
                DISTINCT tagging.taggable_id
                ORDER BY tagging.id ASC
                SEPARATOR ','
              )
              FROM tagging INNER JOIN tag ON (tag.id = tagging.tag_id AND tag.is_triple = 0 AND tag.slug %s ('%s'))
              WHERE tagging.taggable_model = 'Collectible'
              GROUP BY tagging.taggable_model
          )
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
   * @param  array   $keys
   * @param  string  $comparison
   *
   * @return CollectibleQuery
   */
  public function filterByMachineTags($tags, $namespace, $keys = array('all'), $comparison = Criteria::IN)
  {
    $tags = !is_array($tags) ? explode(',', (string) $tags) : $tags;
    $tags = array_map('addslashes', $tags);

    // Make sure $keys is array
    $keys = (array) $keys;

    $where = sprintf("
        collectible.ID IN (
          SELECT tagging.taggable_id FROM (
            SELECT tag.id from tag
             WHERE
              tag.is_triple = 1 AND
              tag.triple_value %s ('%s') AND
              tag.triple_namespace = '%s' AND
              tag.triple_key IN ('%s')
          ) res INNER JOIN tagging ON (res.id = tag_id AND taggable_model = 'Collectible')
        )
      ",
      $comparison === Criteria::NOT_IN ? 'NOT IN' : 'IN',
      implode("','", $tags), $namespace, implode("','", $keys)
    );

    return $this->where($where);
  }

  /**
   * @param  array   $tags
   * @param  string  $namespace
   * @param  array   $keys
   * @param  string  $comparison
   *
   * @return CollectibleQuery
   */
  public function orderByMachineTags($tags, $namespace, $keys = array('all'), $comparison = Criteria::IN)
  {
    $tags = !is_array($tags) ? explode(',', (string) $tags) : $tags;
    $tags = array_map('addslashes', $tags);

    // Make sure $keys is array
    $keys = (array) $keys;

    $order = sprintf("
        FIELD(
          collectible.ID,
          (
            SELECT
              GROUP_CONCAT(
                DISTINCT tagging.taggable_id
                ORDER BY FIELD(tag.triple_key, '%s') ASC, tagging.id ASC
                SEPARATOR ','
              )
              FROM tagging INNER JOIN tag ON (
                tag.id = tagging.tag_id AND tag.is_triple = 1 AND
                tag.triple_value %s ('%s') AND
                tag.triple_namespace = '%s' AND
                tag.triple_key IN ('%s')
              )
              WHERE tagging.taggable_model = 'Collectible'
              GROUP BY tagging.taggable_model
          )
        )
      ",
      implode("','", $keys), $comparison === Criteria::NOT_IN ? 'NOT IN' : 'IN',
      implode("','", $tags), $namespace, implode("','", $keys)
    );

    return $this->addDescendingOrderByColumn($order);
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
      ->where('EXISTS ( SELECT 1 FROM '.CollectionCollectiblePeer::TABLE_NAME.' WHERE '.CollectionCollectiblePeer::COLLECTIBLE_ID.' = Collectible.Id )');
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

  /**
   * @param     array|PropelObjectCollection|ContentCategory  $content_category
   * @param     string  $comparison
   *
   * @return    CollectibleQuery
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

}
