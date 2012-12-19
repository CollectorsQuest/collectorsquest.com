<?php

require 'lib/model/om/BaseCollectorCollectionQuery.php';

class CollectorCollectionQuery extends BaseCollectorCollectionQuery
{

  /**
   * @var boolean Have we modified the CollectorCollection table map
   */
  protected static $table_map_modified = false;

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
          SELECT tagging.taggable_id from (
            SELECT tag.id from tag WHERE tag.is_triple = 0 AND tag.slug %s ('%s')
          ) res INNER JOIN tagging ON (res.id = tag_id AND taggable_model = 'CollectorCollection')
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
   * @return CollectorCollectionQuery
   */
  public function filterByMachineTags($tags, $namespace, $keys = array('all'), $comparison = Criteria::IN)
  {
    $tags = !is_array($tags) ? explode(',', (string) $tags) : $tags;
    $tags = array_map('addslashes', $tags);

    // Make sure $keys is array
    $keys = (array) $keys;

    $where = sprintf("
        collector_collection.ID IN (
          SELECT tagging.taggable_id FROM (
            SELECT tag.id from tag
             WHERE
              tag.is_triple = 1 AND
              tag.triple_value %s ('%s') AND
              tag.triple_namespace = '%s' AND
              tag.triple_key IN ('%s')
          ) res INNER JOIN tagging ON (res.id = tag_id AND taggable_model = 'CollectorCollection')
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
   * @return CollectorCollectionQuery
   */
  public function orderByMachineTags($tags, $namespace, $keys = array('all'), $comparison = Criteria::IN)
  {
    $tags = !is_array($tags) ? explode(',', (string) $tags) : $tags;
    $tags = array_map('addslashes', $tags);

    // Make sure $keys is array
    $keys = (array) $keys;

    $order = sprintf("
        FIELD(
          collector_collection.ID,
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
              WHERE tagging.taggable_model = 'CollectorCollection'
              GROUP BY tagging.taggable_model
          )
        )
      ",
      implode("','", $keys), $comparison === Criteria::NOT_IN ? 'NOT IN' : 'IN',
      implode("','", $tags), $namespace, implode("','", $keys)
    );

    return $this->addDescendingOrderByColumn($order);
  }

  /**
   * @return CollectorCollectionQuery
   */
  public function hasThumbnail()
  {
    // @todo: we need implementation
    return $this;
  }

  /**
   * Filter on only collections that have collectibles for sale
   *
   * @return    ContentCategoryQuery
   */
  public function hasCollectiblesForSale()
  {
    return $this
       ->useCollectionCollectibleQuery()
        ->isForSale()
      ->endUse()
      ->groupById();
  }


  /**
   * @return CollectorCollectionQuery
   */
  public function isComplete()
  {
    return $this->filterByIsPublic(true, Criteria::EQUAL);
  }

  /**
   * @return CollectorCollectionQuery
   */
  public function isIncomplete()
  {
    return $this->filterByIsPublic(false, Criteria::EQUAL);
  }

  /**
   * {@inheritdoc}
   */
  public function useCollectionCollectibleQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
  {
    $this->prepareExtraRelations();

    return parent::useCollectionCollectibleQuery($relationAlias, $joinType);
  }

  /**
   * @return CollectorCollectionQuery
   */
  public function prepareExtraRelations()
  {
    // if we have not modified the table map yet
    if (false === self::$table_map_modified)
    {
      $table_map = CollectorCollectionPeer::getTableMap();
      // add a noSQL relation between CollectorCollection and CollectionCollectible
      $table_map->addRelation('CollectionCollectible', 'CollectionCollectible', RelationMap::MANY_TO_ONE, array('id' => 'collection_id', ), 'SET NULL', null);
    }

    return $this;
  }

}
