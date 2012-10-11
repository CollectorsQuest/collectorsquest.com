<?php

require 'lib/model/om/BaseCollectible.php';

/**
 * IceTaggableBehavior
 *
 * @method array getTags($options = array())
 * @method boolean setTags($names)
 * @method boolean addTag($name)
 * @method boolean hasTag($name)
 */
class Collectible extends BaseCollectible implements ShippingReferencesInterface
{
  /** @var array */
  public $_multimedia = array();

  /** @var array */
  public $_counts = array();

  public function postSave(PropelPDO $con = null)
  {
    parent::postSave($con);

    $this->updateIsPublic($con);
  }

  public function __toString()
  {
    return parent::__toString() ?: 'Untitled';
  }

  public function getGraphId()
  {
    $graph_id = null;

    if (!$this->isNew() && (!$graph_id = parent::getGraphId()))
    {
      $client = cqStatic::getNeo4jClient();

      try
      {
        $node = $client->makeNode();
        $node->setProperty('model', 'Collectible');
        $node->setProperty('model_id', $this->getId());
        $node->save();

        $graph_id = $node->getId();
      }
      catch(Everyman\Neo4j\Exception $e)
      {
        $graph_id = null;
      }

      try
      {
        $this->setGraphId($graph_id);
        $this->save();
      }
      catch (PropelException $e)
      {
        $graph_id = parent::getGraphId();
      }
    }

    return $graph_id;
  }

  /**
   * Convinience method
   *
   * @return Collectible
   */
  public function getCollectible()
  {
    return $this;
  }

  public function getSlug()
  {
    $slug = parent::getSlug();

    return ($slug == '') ? 'n-a' : $slug;
  }

  public function setName($v, $is_automatic = false)
  {
    $this->setIsNameAutomatic($is_automatic);

    parent::setName(IceStatic::cleanText($v, false, 'none'));
  }

  /**
   * Set the description of the collectible
   *
   * @param  string  $v     The description text itself
   * @param  string  $type  Can be only 'html' for now
   */
  public function setDescription($v, $type = 'html')
  {
    if ($type == 'html')
    {
      $v = IceStatic::cleanText(
        $v, false,
        'p, b, u, i, em, strong, h3, h4, h5, h6, div, span, ul, ol, li, blockquote, br'
      );
    }

    parent::setDescription($v);
  }

  public function getDescription($type = 'html', $limit = 0)
  {
    $v = parent::getDescription();

    switch ($type)
    {
      case 'stripped':
        $v = trim(strip_tags($v));
        $v = (intval($limit) > 0) ? cqStatic::truncateText($v, $limit, '...', true) : $v;
        break;
      case 'html':
        break;
    }

    return $v;
  }

  public function getRelatedCollectibles($limit = 8, &$rnd_flag = false)
  {
    /** @var $q CollectibleQuery */
    $q = CollectibleQuery::create()
      ->limit($limit)
      ->addAscendingOrderByColumn('RAND()');

    $rnd_flag = true;

    return $q->find();
  }

  public function getRelatedCollectiblesForSale($limit = 5, &$rnd_flag = false)
  {
    /** @var $q CollectibleForSaleQuery */
    $q = CollectibleForSaleQuery::create()
      ->joinWith('Collectible')
      ->limit($limit)
      ->addAscendingOrderByColumn('RAND()');

    $rnd_flag = true;

    return $q->find();
  }

  public function getRelatedCollections($limit = 5, &$rnd_flag = false)
  {
    $collections = CollectorCollectionPeer::getRelatedCollections($this, $limit);

    if ($limit != $found = count($collections))
    {
      $limit = $limit - $found;
      $sf_context = cqContext::getInstance();

      /** @var $sf_user cqBaseUser */
      $sf_user = $sf_context->getUser();

      if ($sf_context && $sf_user->isAuthenticated())
      {
        $collector = $sf_user->getCollector();
        $c = new Criteria();
        $c->add(CollectorCollectionPeer::ID, $this->getId(), Criteria::NOT_EQUAL);
        $c->add(CollectorCollectionPeer::COLLECTOR_ID, $collector->getId(), Criteria::NOT_EQUAL);
        $c->addAscendingOrderByColumn('RAND()');

        $collections = array_merge($collections, CollectorCollectionPeer::getRelatedCollections($collector, $limit, $c));
      }
    }

    if (0 == count($collections))
    {
      $c = new Criteria();
      $c->add(CollectorCollectionPeer::ID, $this->getCollectionId(), Criteria::NOT_EQUAL);

      $collections = CollectorCollectionPeer::getRandomCollections($limit, $c);
      $rnd_flag = true;
    }

    return $collections;
  }

  /**
   * @return array
   */
  public function getCollectionIds()
  {
    /** @var $q CollectionQuery */
    $q = CollectionQuery::create()
       ->filterByCollectible($this)
       ->setFormatter(ModelCriteria::FORMAT_STATEMENT)
       ->addSelectColumn('collection_id');

    /** @var $stmt PDOStatement */
    $stmt = $q->find();

    return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
  }

  /**
   * @return integer
   */
  public function getCollectionId()
  {
    /** @var $q CollectionQuery */
    $q = CollectionQuery::create()
       ->filterByCollectible($this)
       ->setFormatter(ModelCriteria::FORMAT_STATEMENT)
       ->setSingleRecord(true)
       ->addSelectColumn('collection_id');

    /** @var $stmt PDOStatement */
    $stmt = $q->find();

    return (int) $stmt->fetchColumn(0);
  }

  /**
   * @param  integer  $id
   * @return boolean
   */
  public function setCollectionId($id = null)
  {
    // Setting the Collection ID to null should be a defualt behavior, thus the 'return true'
    if (null === $id)
    {
      return true;
    }

    $q = CollectionCollectibleQuery::create()
       ->filterByCollectible($this)
       ->filterByIsPrimary(true);

    try
    {
      $collection_collectible = $q->findOneOrCreate();
      $collection_collectible->setCollectionId($id);
      $collection_collectible->save();
    }
    catch (PropelException $e)
    {
      return false;
    }

    return true;
  }

  /**
   * @param  Collection|null  $collection
   * @return boolean
   */
  public function setCollection(Collection $collection = null)
  {
    // Setting the Collection to null should be a defualt behavior, thus the 'return true'
    if (null === $collection)
    {
      return true;
    }

    $q = CollectionCollectibleQuery::create()
       ->filterByCollectible($this)
       ->filterByIsPrimary(true);

    try
    {
      $collection_collectible = $q->findOneOrCreate();
      $collection_collectible->setCollection($collection);
      $collection_collectible->save();
    }
    catch (PropelException $e)
    {
      return false;
    }

    return true;
  }

  public function setCollections(PropelCollection $collections, PropelPDO $con = null)
  {
    $collectionCollectibles = CollectionCollectibleQuery::create()
      ->filterByCollection($collections)
      ->filterByCollectible($this)
      ->find($con);

    $this->collectionsScheduledForDeletion = $this->getCollectionCollectibles()->diff($collectionCollectibles, false);
    $this->collCollectionCollectibles = $collectionCollectibles;

    foreach ($collections as $collection)
    {
      // Fix issue with collection modified by reference
      if ($collection->isNew())
      {
        $this->doAddCollection($collection);
      }
      else
      {
        $this->addCollection($collection);
      }
    }

    $this->collCollections = $collections;
  }

  /**
   * Associate a Collection object to this object
   * through the collection_collectible cross reference table.
   *
   * @param      Collection $collection The CollectionCollectible object to relate
   * @return     void
   */
  public function addCollection(Collection $collection)
  {
    if ($this->collCollections === null)
    {
      $this->collCollections = $this->getCollections();
    }

    // only add it if the **same** object is not already associated
    if (!$this->collCollections->contains($collection, false))
    {
      $this->doAddCollection($collection);
      $this->collCollections[]= $collection;
    }
  }

  /**
   * @param  PropelPDO  $con
   * @return Collection | CollectionDropbox
   */
  public function getCollection(PropelPDO $con = null)
  {
    /** @var $q CollectionQuery */
    $q = CollectionQuery::create()
       ->filterByCollectible($this);

    if (!$collection = $q->findOne($con))
    {
      $collection = new CollectionDropbox($this->getCollectorId());
    }

    return $collection;
  }

  public function getCollectorCollection(PropelPDO $con = null)
  {
    return $this->getCollection($con)->getCollectorCollection($con);
  }

  public function getCollectionTags()
  {
    return $this->getCollection()->getTags();
  }

  public function getTagString()
  {
    return implode(", ", $this->getTags());
  }

  public function hasTags()
  {
    $c = new Criteria;
    $c->addSelectColumn(iceModelTaggingPeer::TAG_ID);
    $c->add(iceModelTaggingPeer::TAGGABLE_ID, $this->getId());
    $c->add(iceModelTaggingPeer::TAGGABLE_MODEL, 'Collectible');

    return 0 < iceModelTaggingPeer::doCount($c);
  }

  public function getTagIds()
  {
    $c = new Criteria;
    $c->addSelectColumn(iceModelTaggingPeer::TAG_ID);
    $c->add(iceModelTaggingPeer::TAGGABLE_ID, $this->getId());
    $c->add(iceModelTaggingPeer::TAGGABLE_MODEL, 'Collectible');
    $stmt = iceModelTaggingPeer::doSelectStmt($c);

    return $stmt->fetchAll(PDO::FETCH_COLUMN);
  }

  public function getAmazonKeywords()
  {
    $tags = $this->getTags();
    $keywords = (!empty($tags)) ? $tags : TermPeer::getTerms($this);

    if (empty($keywords))
    {
      $keywords = $this->getCollectionTags();
    }

    return $keywords;
  }

  public function getEbayKeywords()
  {
    $tags = $this->getTags();
    $keywords = (!empty($tags)) ? $tags : TermPeer::getTerms($this);
    if (empty($keywords))
    {
      $keywords = $this->getCollectionTags();
    }
    shuffle($keywords);

    return str_replace(' ', '+', implode('+', (array) array_slice($keywords, 0, (count($keywords) < 2) ? count($keywords) : 2)));
  }

  /**
   * @dericated
   *
   * @param $file
   * @param bool $queue
   *
   * @return mixed
   */
  public function setThumbnail($file, $queue = false)
  {
    return $this->setPrimaryImage($file);
  }

  public function rotateMultimedia($is_primary = true, $queue = false)
  {
    /** @var $multimedia Multimedia[] */
    $multimedia = $this->getMultimedia(0, 'image', $is_primary);

    if ($multimedia && !is_array($multimedia))
    {
      $multimedia = array($multimedia);
    }

    foreach ($multimedia as $m)
    {
      $m->rotate('original', 90);
    }

    return true;
  }

  public function setCustomFields($fields)
  {
    if (is_array($fields))
    {
      $this->deleteCustomValues();
      $fields = array_filter($fields);
      foreach ($fields as $id => $value)
      {
        if (is_array($value))
          $value = array_filter($value);
        if (empty($value))
          continue;

        $custom = new CustomValue();
        $custom->setCollection($this->getCollection());
        $custom->setCollectibleId($this->getId());
        $custom->setFieldId($id);

        if ($custom->setValue($value))
        {
          $custom->save();
        }
        else
        {
          unset($custom);
        }
      }
    }
  }

  public function getCustomValues($c = null, PropelPDO $con = null)
  {
    if ($c === null)
    {
      $c = new Criteria();
    }
    elseif ($c instanceof Criteria)
    {
      $c = clone $c;
    }
    $c->addAscendingOrderByColumn(CustomValuePeer::FIELD_ID);

    // Initialize the return array
    $custom_values = array();

    /** @var $_custom_values CustomValue[] */
    $_custom_values = parent::getCustomValues($c, $con);

    foreach ($_custom_values as $value)
    {
      $custom_values[$value->getFieldId()] = $value;
    }

    return $custom_values;
  }

  public function deleteCustomValues()
  {
    $c = new Criteria();
    $c->add(CustomValuePeer::COLLECTION_ID, $this->getCollectionId());
    $c->add(CustomValuePeer::COLLECTIBLE_ID, $this->getId());

    return CustomValuePeer::doDelete($c);
  }

  public function isForSale(PropelPDO $con = null)
  {
    return $this->getCollectibleForSale($con)
      ? $this->getCollectibleForSale($con)->isForSale()
      : false;
  }

  /**
   * Check if the collectible has been sold
   *
   * @param     PropelPDO $con
   * @return    bool
   */
  public function isSold(PropelPDO $con = null)
  {
    return $this->getCollectibleForSale($con)
      ? $this->getCollectibleForSale($con)->getIsSold()
      : false;
  }

  public function isWasForSale(PropelPDO $con = null)
  {
    return $this->isForSale($con) || $this->isSold($con);
  }

  /**
   * Get the shipping references for this collectible, grouped by country, merged
   * with the shipping references for the related collector
   *
   * @param     PropelPDO $con
   * @return    array ShippingReference[]
   */
  public function getShippingReferencesByCountryCode(PropelPDO $con = null)
  {
    return array_merge(
      ShippingReferenceQuery::create()
        ->filterByCollector($this->getCollector($con))
        ->find($con)->getArrayCopy($keyColumn = 'CountryIso3166'),
      ShippingReferenceQuery::create()
        ->filterByCollectible($this)
        ->find($con)->getArrayCopy($keyColumn = 'CountryIso3166')
    );
  }

  /**
   * Get the shipping reference for a specific country
   *
   * If no shipping reference is present for the collectible, the shipping
   * reference for the related collector will be returned (if there is one)
   *
   * @param     string $coutry_code
   * @param     PropelPDO $con
   *
   * @return    ShippingReference
   */
  public function getShippingReferenceForCountryCode($coutry_code, PropelPDO $con = null)
  {
    return (
      ShippingReferenceQuery::create()
        ->filterByCollectible($this)
        ->filterByCountryIso3166($coutry_code)
        ->findOne($con)
      ?: ShippingReferenceQuery::create()
        ->filterByCollectible($this)
        ->filterByCountryIso3166('ZZ') // international
        ->findOne($con)
    )
    ?: ShippingReferenceQuery::create()
      ->filterByCollector($this->getCollector())
      ->filterByCountryIso3166($coutry_code)
      ->findOne($con); // for collector
  }

  /**
   * Get the shipping reference for this collectible's collector's country
   *
   * @param     PropelPDO $con
   * @return    ShippingReference
   */
  public function getShippingReferenceDomestic(PropelPDO $con = null)
  {
    return $this->getShippingRateForCountryCode(
      $this->getCollector($con)->getProfile($con)->getCountryIso3166(), $con);
  }

  public function updateIsPublic(PropelPDO $con = null)
  {
    if ($con === null)
    {
      $con = Propel::getConnection(
        CollectiblePeer::DATABASE_NAME, Propel::CONNECTION_WRITE
      );
    }

    // Start with the current public status of the Collectible
    $is_public = $this->getIsPublic();

    // We want to enforce the public status only on records after 15th of September, 2012
    if ($this->getCreatedAt('U') > 1347667200 || $is_public === false)
    {
      if (!$this->getName())
      {
        $is_public = false;
      }
      else if (!$this->getDescription())
      {
        $is_public = false;
      }
      else if (!$this->getTags())
      {
        $is_public = false;
      }
      else if (!$this->getPrimaryImage(Propel::CONNECTION_WRITE))
      {
        $is_public = false;
      }
      else
      {
        $is_public = true;
      }
    }

    // Update only if there is a change of the public status
    if ($is_public !== $this->getIsPublic())
    {
      $this->setIsPublic($is_public);

      $sql = sprintf(
        'UPDATE %s SET %s = %d WHERE %s = %d',
        CollectiblePeer::TABLE_NAME, CollectiblePeer::IS_PUBLIC, $is_public,
        CollectiblePeer::ID, $this->getId()
      );
      $con->exec($sql);
    }
  }

  /**
   * For each Multimedia that is added to the Advert, this method will be called
   * to take care of creating the right thumnail sizes
   *
   * @param  iceModelMultimedia  $multimedia
   * @param  array $options
   *
   * @throws InvalidArgumentException
   * @return void
   */
  public function createMultimediaThumbs(iceModelMultimedia $multimedia, $options = array())
  {
    $watermark = isset($options['watermark']) ? (boolean) $options['watermark'] : false;

    /**
     * We need to have the four main thumbnails available as soon as the object is saved so
     * we make sure they are not put on the job queue
     */
    $multimedia->makeThumb(150, 150, 'top', false);
    $multimedia->makeCustomThumb(190, 190, '190x190', 'top', false);
    $multimedia->makeCustomThumb(620, 0, '620x0', 'resize', $watermark);

    // The rest of the thumnails are not immediately used so they can be deferred
    $multimedia->makeCustomThumb(75, 75, '75x75', 'top', false);
    $multimedia->makeCustomThumb(190, 150, '190x150', 'top', false);
    $multimedia->makeCustomThumb(260, 205, '260x205', 'top', $watermark);
  }

  /**
   * @param  null|PropelPDO  $con
   * @return boolean
   */
  public function preDelete(PropelPDO $con = null)
  {
    // Delete shipping references manually because no actual FK exists
    ShippingReferenceQuery::create()
      ->filterByCollectible($this)
      ->delete($con);

    // Deleting collectibles for sale
    $collectible_for_sale = $this->getCollectibleForSale();
    if (!empty($collectibles_for_sale))
    {
      $collectible_for_sale->delete($con);
    }

    return parent::preDelete($con);
  }

  /**
   * @param Collector $collector
   * @param bool $rated
   * @return ShoppingOrderFeedback
   */
  public function getShoppingOrderFeedbackFromBuyer(Collector $collector, $rated = true)
  {
    $criteria = new Criteria();
    $criteria->add(ShoppingOrderFeedbackPeer::BUYER_ID, $collector->getId());
    $criteria->add(ShoppingOrderFeedbackPeer::IS_RATED, $rated);

    return $this->getShoppingOrderFeedbacks($criteria)->getFirst();
  }

}

sfPropelBehavior::add('Collectible', array('IceMultimediaBehavior'));
sfPropelBehavior::add('Collectible', array('IceTaggableBehavior'));

sfPropelBehavior::add(
  'Collectible',
  array('PropelActAsEblobBehavior' => array('column' => 'eblob')
));

sfPropelBehavior::add(
  'Collectible',
  array(
    'PropelActAsSluggableBehavior' => array(
      'columns' => array(
        'from' => CollectiblePeer::NAME,
        'to' => CollectiblePeer::SLUG
      ),
      'separator' => '-',
      'permanent' => false,
      'lowercase' => true,
      'ascii' => true,
      'chars' => 128
    )
  )
);
