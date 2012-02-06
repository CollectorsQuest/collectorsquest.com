<?php

/**
 * Subclass for performing query and update operations on the 'collection_item' table.
 *
 *
 *
 * @package lib.model
 */
class CollectionItemPeer extends BaseCollectionItemPeer
{
  public static function createNewRecord($data = array())
  {
    $item = new CollectionItem();
    $item->setCollectionId($data['collection_id']);

    // title should be changed to 'name'
    $item->setName($data['name']);
    $item->setDescription($data['description']);
    $item->setIsForSale($data['sale']);

    // We need to have the Item object "save()"ed before we can set the photo
    $item->setPhoto($data['fileName']);
    $item->addTag($data['tags']);
    $item->save();

    return $item;
  }

  public static function getPopularTags($limit = 50)
  {
    $c = new Criteria();
    $c->add(iceModelTagPeer::NAME, 'CHAR_LENGTH('. iceModelTagPeer::NAME .') > 2', Criteria::CUSTOM);
    $c->setLimit($limit);

    return iceModelTagPeer::getPopulars($c, array('model' => 'CollectionItem'));
  }

  public static function getPopularByTag($tag, $limit = 6)
  {
    $c = new Criteria();
    $c->add(iceModelTagPeer::NAME, $tag);
    $c->addJoin(iceModelTaggingPeer::TAG_ID, iceModelTagPeer::ID);
    $c->add(iceModelTaggingPeer::TAGGABLE_MODEL, 'CollectionItem');
    $c->addJoin(CollectionItemPeer::ID, iceModelTaggingPeer::TAGGABLE_ID, Criteria::LEFT_JOIN);
    $c->setLimit($limit);

    $items = CollectionItemPeer::doSelect($c);

    return $items;
  }

  public static function getLatestItems($limit = 18)
  {
    $c = new Criteria();
    $c->addDescendingOrderByColumn(CollectionItemPeer::ID);
    $c->setLimit($limit);

    return CollectionItemPeer::doSelect($c);
  }

  public static function search($search = array(), $only_pks = false)
  {
    $search_index = new cqSearchIndex('CollectionItem');

    $index = $search_index->getLuceneSearchIndex();
    $query = '';
    if (!empty($search['term']))
    {
      $query .= ' +"'.$search['term'].'"';
      if (is_numeric($search['term']))
      {
        $query .= ' -collection_id:"'.$search['term'].'"';
        $query .= ' -collector_id:"'.$search['term'].'"';
        $query .= ' -item_id:"'.$search['term'].'"';
      }
    }
    if (!empty($search['tag'])) {
      $query .= ' +tags:"'.$search['tag'].'"';
    }
    if (!empty($search['collector']) && $search['collector'] instanceof Collector) {
      $query .= ' +collector_id:'.$search['collector']->getId();
    }
    try {
      $query = Zend_Search_Lucene_Search_QueryParser::parse(trim($query));
      $hits = $index->find($query);
    } catch (Zend_Search_Lucene_Search_QueryParserException $e) {
      $hits = array();
    }

    $pks = array();
    foreach ($hits as $hit) {
      $pks[] = (int)$hit->item_id;
    }

    cqPropelPrivateBehavior::enable();
    $results = ($only_pks)?$pks:CollectionItemPeer::retrieveByPKs($pks);
    cqPropelPrivateBehavior::restore();

    return $results;
  }

	/* added by Prakash Panchal 29-3-2011
	 * getCollectionAsPerCollector function.
	 * return object
	 */
	public static function getCollectionItemAsPerCollection($snIdCollection)
	{
		$oCriteria = new Criteria();
		$oCriteria->addSelectColumn(CollectionItemPeer::ID);
		$oCriteria->addSelectColumn(CollectionItemPeer::NAME);
		$oCriteria->add(CollectionItemPeer::COLLECTION_ID, $snIdCollection);
		$oCriteria->add(CollectionItemPeer::ID, CollectionItemPeer::ID.' NOT IN (SELECT item_id FROM collection_item_for_sale)', Criteria::CUSTOM);
		$oCriteria->addAscendingOrderByColumn(CollectionItemPeer::NAME);

		return $omResultStatement = CollectionItemPeer::doSelectStmt($oCriteria);
	}
	/* added by Prakash Panchal 5-4-2011
	 * getCustomValues function.
	 * return array
	 */
	public static function getCustomValues($c = null, PropelPDO $con = null)
	{
		if ($c === null) {
			$c = new Criteria();
		}
		elseif ($c instanceof Criteria)
		{
			$c = clone $c;
		}
		$c->addJoin(CustomFieldPeer::ID, CustomValuePeer::FIELD_ID);
		$c->addAscendingOrderByColumn(CustomValuePeer::FIELD_ID);
		$omCustomValue = CustomValuePeer::doSelect($c);
		$custom_values = array();
		foreach ($omCustomValue as $value)
		{
			$custom_values[$value->getFieldId()] = $value;
		}
		return $custom_values;
	}
	/* added by Prakash Panchal 5th-May-2011
	 * checkIsCollectionItemExists function.
	 * return object
	 */
	public static function checkIsCollectionItemExists($snCollectibleId, $snCollectionId, $ssSlug)
	{
		$oCriteria = new Criteria();
		$oCriteria->add(CollectionItemPeer::COLLECTIBLE_ID, $snCollectibleId);
		$oCriteria->add(CollectionItemPeer::COLLECTION_ID, $snCollectionId);

		$omCollectionItem = CollectionItemPeer::doSelectOne($oCriteria);
		return ($omCollectionItem) ? $omCollectionItem : false;
	}
}
