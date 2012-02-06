<?php

class CollectionPeer extends BaseCollectionPeer
{
  static public function getObjectForRoute($parameters)
  {
    $collection = null;

    if (isset($parameters['collection_id']))
    {
      $collection = self::retrieveByPk($parameters['collection_id']);
    }
    else if (isset($parameters['id']))
    {
      $collection = self::retrieveByPk($parameters['id']);
    }

    return $collection;
  }

  public static function getPopularTags($limit = 50, Criteria $criteria = null)
  {
    $c = ($criteria instanceof Criteria) ? clone $criteria : new Criteria();

    $c->add(iceModelTagPeer::NAME, 'CHAR_LENGTH('. iceModelTagPeer::NAME .') > 2', Criteria::CUSTOM);
    $c->setLimit($limit);

    return iceModelTagPeer::getPopulars($c, array('model' => 'Collection'));
  }

  public static function getPopularByTag($tags, $limit = 10, Criteria $criteria = null)
  {
    $c = ($criteria instanceof Criteria) ? clone $criteria : new Criteria();
    $c->setDistinct();

    $regex = iceModelTagPeer::NAME . " REGEXP '[[:<:]]".str_replace("'", "", implode('|', $tags))."[[:>:]]'";
    $c->add(iceModelTagPeer::NAME, $regex, Criteria::CUSTOM);

    $c->addJoin(iceModelTaggingPeer::TAG_ID, iceModelTagPeer::ID);
    $c->add(iceModelTaggingPeer::TAGGABLE_MODEL, 'Collection');
    $c->addJoin(CollectionPeer::ID, iceModelTaggingPeer::TAGGABLE_ID, Criteria::LEFT_JOIN);
    $c->add(CollectionPeer::NUM_ITEMS, 3, Criteria::GREATER_EQUAL);
    $c->addDescendingOrderByColumn(CollectionPeer::NUM_ITEMS);
    $c->setLimit($limit);

    return CollectionPeer::doSelectJoinCollector($c);
  }

  public static function getPopularByCountry($country, $limit = 10, Criteria $criteria = null)
  {
    $c = ($criteria instanceof Criteria) ? clone $criteria : new Criteria();

    $c->setDistinct();
    $c->addJoin(CollectionPeer::COLLECTOR_ID, CollectorProfilePeer::COLLECTOR_ID);
    $c->add(CollectorProfilePeer::COUNTRY, $country);
    $c->setLimit($limit);

    return CollectionPeer::doSelectJoinCollector($c);
  }

  public static function getPopularByZip($zips, $limit = 10, Criteria $criteria = null)
  {
    if (empty($zips) || !is_array($zips))
    {
      return null;
    }

    $c = ($criteria instanceof Criteria) ? clone $criteria : new Criteria();

    $c->setDistinct();
    $c->addJoin(CollectionPeer::COLLECTOR_ID, CollectorProfilePeer::COLLECTOR_ID);
    $c->add(CollectorProfilePeer::ZIP, $zips, Criteria::IN);
    $c->setLimit($limit);

    return CollectionPeer::doSelectJoinCollector($c);
  }

  public static function getRandomCollections($limit = 10, Criteria $criteria = null)
  {
    $c = ($criteria instanceof Criteria) ? clone $criteria : new Criteria();

    $c->setDistinct();
    $c->add(CollectionPeer::NUM_ITEMS, 3, Criteria::GREATER_EQUAL);
    $c->addAscendingOrderByColumn('RAND()');
    $c->setLimit($limit);

    return CollectionPeer::doSelectJoinCollector($c);
  }

  /**
   * @static
   *
   * @param  BaseObject  $object
   * @param  integer     $limit
   * @param  Criteria    $criteria
   *
   * @return Collection[]
   */
  public static function getRelatedCollections($object, $limit = 0, Criteria $criteria = null)
  {
    $pks = array();

    if ($tag_ids = $object->getTagIds())
    {
      $tag_ids = iceModelTagPeer::removeTagsForRelatedItems($tag_ids);

      $c = (is_null($criteria)) ? new Criteria() : clone $criteria;
      $c->setDistinct();
      $c->addSelectColumn(CollectionPeer::ID);
      $c->addAsColumn(
        'tags_count',
        sprintf(
          "(SELECT DISTINCT COUNT(t.id) FROM %s t WHERE t.taggable_model = 'Collection' AND t.taggable_id = %s GROUP BY t.taggable_id)",
          iceModelTaggingPeer::TABLE_NAME, iceModelTaggingPeer::TAGGABLE_ID
        )
      );
      $c->addJoin(CollectionPeer::ID, iceModelTaggingPeer::TAGGABLE_ID);
      $c->add(CollectionPeer::ID, 767, Criteria::NOT_EQUAL);
      $c->add(CollectionPeer::NUM_ITEMS, 3, Criteria::GREATER_EQUAL);
      $c->add(iceModelTaggingPeer::TAGGABLE_MODEL, 'Collection');
      if (!empty($tag_ids)) $c->add(iceModelTaggingPeer::TAG_ID, $tag_ids, Criteria::IN);
      $c->addAscendingOrderByColumn('tags_count');
      $c->addAscendingOrderByColumn(iceModelTaggingPeer::TAG_ID);
      $c->setLimit($limit);

      switch (get_class($object))
      {
        case 'Collection':
          /** @var $object Collection */
          $c->add(CollectionPeer::ID, $object->getId(), Criteria::NOT_EQUAL);
          break;
        case 'Collector':
          /** @var $object Collector */
          $c->add(CollectionPeer::COLLECTOR_ID, $object->getId(), Criteria::NOT_EQUAL);
          break;
        case 'Collectible':
          /** @var $object Collectible */
          $c->add(CollectionPeer::ID, $object->getCollection()->getId(), Criteria::NOT_EQUAL);
          break;
      }

      try
      {
        $stmt = CollectionPeer::doSelectStmt($c);
        while ($pk = $stmt->fetchColumn(0))
        {
          array_push($pks, (int) $pk);
        }
      }
      catch (Exception $e)
      {
        $pks = array();
      }
    }

    if ($term_ids = TermPeer::getTermIds($object))
    {
      $c = (is_null($criteria)) ? new Criteria() : clone $criteria;
      $c->setDistinct();
      $c->addSelectColumn(CollectionPeer::ID);
      $c->add(CollectionPeer::ID, 767, Criteria::NOT_EQUAL);

      if (count($pks) > $limit && $limit != 0) {
        $c->add(CollectionPeer::ID, $pks, Criteria::IN);
      }

      $c->addJoin(CollectionPeer::ID, TermRelationshipPeer::MODEL_ID);
      $c->add(CollectionPeer::NUM_ITEMS, 3, Criteria::GREATER_EQUAL);
      $c->add(TermRelationshipPeer::TERM_ID, $term_ids, Criteria::IN);
      $c->add(TermRelationshipPeer::MODEL, 'Collection');
      $c->addAscendingOrderByColumn(TermRelationshipPeer::TERM_ID);

      switch (get_class($object))
      {
        case 'Collection':
          $c->add(CollectionPeer::ID, $object->getId(), Criteria::NOT_EQUAL);
          break;
        case 'Collector':
          $c->add(CollectionPeer::COLLECTOR_ID, $object->getId(), Criteria::NOT_EQUAL);
          break;
        case 'CollectionItem':
          $c->add(CollectionPeer::ID, $object->getCollection()->getId(), Criteria::NOT_EQUAL);
          break;
      }

      try
      {
        $stmt = CollectionPeer::doSelectStmt($c);
        while ($pk = $stmt->fetchColumn(0))
        {
          array_push($pks, (int) $pk);
        }
      }
      catch (PropelException $e)
      {
        $pks = array();
      }
    }

    // Check if we have enough $pks and if not get the collection category tags and get
    // collections for those tags
    if ($limit != 0 && count($pks) < $limit)
    {
      $c = (is_null($criteria)) ? new Criteria() : clone $criteria;
      $c->setDistinct();
      $c->addSelectColumn(CollectionPeer::ID);
      $c->add(CollectionPeer::ID, 767, Criteria::NOT_EQUAL);
      $c->addJoin(CollectionPeer::ID, iceModelTaggingPeer::TAGGABLE_ID);
      $c->add(CollectionPeer::NUM_ITEMS, 3, Criteria::GREATER_EQUAL);
      $c->add(iceModelTaggingPeer::TAGGABLE_MODEL, 'Collection');
      $c->addDescendingOrderByColumn(CollectionPeer::NUM_ITEMS);

      switch (get_class($object))
      {
        case 'Collection':
          $tag_ids = $object->getCollectionCategory()->getTagIds();
          $c->add(CollectionPeer::ID, $object->getId(), Criteria::NOT_EQUAL);
          break;
        case 'Collector':
          $collection_category_ids = $object->getCollectionCategoryIds();

          $k = new Criteria;
          $k->addSelectColumn(iceModelTaggingPeer::TAG_ID);
          if (!empty($collection_category_ids))
          {
            $k->add(iceModelTaggingPeer::TAGGABLE_ID, $collection_category_ids, Criteria::IN);
          }
          $k->add(iceModelTaggingPeer::TAGGABLE_MODEL, 'CollectionCategory');

          try
          {
            $tag_ids = array();

            $stmt = iceModelTaggingPeer::doSelectStmt($k);
            while ($tag_id = $stmt->fetchColumn(0))
            {
              $tag_ids[] = (int) $tag_id;
            }
          }
          catch (PropelException $e)
          {
            $tag_ids = array();
          }

          $c->add(CollectionPeer::COLLECTOR_ID, $object->getId(), Criteria::NOT_EQUAL);
          break;
        case 'CollectionItem':
          $tag_ids = $object->getCollection()->getCollectionCategory()->getTagIds();
          $c->add(CollectionPeer::ID, $object->getCollection()->getId(), Criteria::NOT_EQUAL);
          break;
      }

      if (!empty($tag_ids))
      {
        $c->add(iceModelTaggingPeer::TAG_ID, $tag_ids, Criteria::IN);
      }

      $stmt = CollectionPeer::doSelectStmt($c);
      while ($pk = $stmt->fetchColumn(0)) {
        array_push($pks, (int) $pk);
      }
    }

    $c = new Criteria();
    $c->add(CollectionPeer::ID, array_slice($pks, 0, ($limit != 0)?$limit:count($pks)), Criteria::IN);

    return CollectionPeer::doSelectJoinCollector($c);
  }
}
