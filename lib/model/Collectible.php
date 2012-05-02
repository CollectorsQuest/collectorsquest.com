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
class Collectible extends BaseCollectible
{
  public
    $_multimedia = array(),
    $_counts = array();

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

      $this->setGraphId($graph_id);
      $this->save();
    }

    return $graph_id;
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
   * @param  string  $type  Can be 'html' or 'markdown'
   */
  public function setDescription($v, $type = 'markdown')
  {
    if ($type == 'html')
    {
      $v = IceStatic::cleanText($v, false, 'p, b, u, i, em, strong, h1, h2, h3, h4, h5, h6, div, span, ul, ol, li, blockquote');
      $v = cqMarkdownify::doConvert($v);
    }

    parent::setDescription($v);
  }

  public function getDescription($type = 'html', $limit = null)
  {
    $v = parent::getDescription();

    switch ($type)
    {
      case 'stripped':
        $v = cqMarkdown::doConvert($v);
        $v = trim(strip_tags($v));
        break;
      case 'html':
        $v = cqMarkdown::doConvert($v);
        break;
    }

    return (null !== $limit) ? cqStatic::truncateText($v, (int) $limit, '...', true) : $v;
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
      $sf_context = sfContext::getInstance();

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

  public function isForSale()
  {
    $c = new Criteria();
    $c->add(CollectibleForSalePeer::COLLECTIBLE_ID, $this->getId());
    $c->add(CollectibleForSalePeer::IS_SOLD, false);

    return CollectibleForSalePeer::doCount($c);
  }

  /**
   * Get the shipping rates for this collectible, grouped by country
   *
   * @param     PropelPDO $con
   * @return    array
   *
   * @see       ShippingRateCollectorQuery::findAndGroupByCountryCode()
   */
  public function getShippingRatesGroupedByCountryCode(PropelPDO $con = null)
  {
    return array_merge(
      ShippingRateCollectorQuery::create()
        ->filterByCollector($this)
        ->findAndGroupByCountryCode($con),
      ShippingRateCollectibleQuery::create()
        ->filterByCollectible($this)
        ->findAndGroupByCountryCode($con)
    );
  }

  /**
   * Get shipping rates for a specific country
   *
   * @param     string $coutry_code
   * @param     PropelPDO $con
   * @return    ShippingRate[]
   */
  public function getShippingRatesForCountryCode($coutry_code, PropelPDO $con = null)
  {
    // get all shipping rates by country,
    // because they hold the combination of base collector shipping rates
    // plus any specific collectible shipping rates set for this object
    $shipping_rates_by_country = $this->getShippingRatesGroupedByCountryCode($con);

    // if we have a shipping fee set for this country
    if (isset($shipping_rates_by_country[$coutry_code]))
    {
      // return it
      return $shipping_rates_by_country[$coutry_code];
    }
    else
    {
      // otherwize return an empty array
      return array();
    }
  }

  /**
   * Get shipping rates for the collectible's collector country
   *
   * @param     PropelPDO $con
   * @return    ShippingRate[]
   */
  public function getShippingRatesDomestic(PropelPDO $con = null)
  {
    $country_code = $this->getCollector()->getProfile()->getCountryIso3166();

    return $this->getShippingRatesForCountryCode($country_code, $con);
  }

  /**
   * Return the domestic country code
   *
   * @return    string
   */
  public function getDomesticCountryCode()
  {
    return $this->getCollector()->getProfile()->getCountryIso3166();
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
    $multimedia->makeThumb(150, 150, 'scale', false);
    $multimedia->makeCustomThumb(190, 190, '190x190', 'scale', false);
    $multimedia->makeCustomThumb('420!', '0', '420!x0', 'top', $watermark);
    $multimedia->makeCustomThumb('620!', '19:15', '620!x19:15', 'top', $watermark);

    // The rest of the thumnails are not immediately used so they can be deferred
    $multimedia->makeCustomThumb(75, 75, '75x75', 'top', false);
    $multimedia->makeCustomThumb(190, 150, '190x150', 'top', false);
    $multimedia->makeCustomThumb(260, 205, '260x205', 'top', $watermark);
    $multimedia->makeCustomThumb(1024, 768, '1024x768', 'scale', $watermark);

    // Here we want to create an optimized thumbnail for the homepage
    if ($multimedia->getOrientation() == 'landscape') {
      $multimedia->makeCustomThumb(230, 150, '230x150', 'center', $watermark);
    } else {
      $multimedia->makeCustomThumb(170, 230, '170x230', 'center', $watermark);
    }
  }

  /**
   * @param  null|PropelPDO  $con
   * @return boolean
   */
  public function preDelete(PropelPDO $con = null)
  {
    // Deleting collectibles for sale
    $collectible_for_sale = $this->getCollectibleForSale();
    if (!empty($collectibles_for_sale))
    {
      $collectible_for_sale->delete($con);
    }

    // Deleting collectibles offers
    $collectible_offers = $this->getCollectibleOffers();
    if (!empty($collectible_offers))
    {
      /** @var $collectible_offer CollectibleOffer */
      foreach ($collectible_offers as $collectible_offer)
      {
        $collectible_offer->delete($con);
      }
    }

    return parent::preDelete($con);
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
