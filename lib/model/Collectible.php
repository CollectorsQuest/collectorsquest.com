<?php

require 'lib/model/om/BaseCollectible.php';

/**
 * IceTaggableBehavior
 *
 * @method array getTags($options = array())
 * @method boolean addTag($name)
 * @method boolean hasTag($name)
 */
class Collectible extends BaseCollectible
{
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

  public function getDescription($type = 'html')
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

    return $v;
  }

  public function getRelatedCollectiblesForSale($limit = 5, &$rnd_flag = false)
  {
    /** @var $q CollectibleForSaleQuery */
    $q = CollectibleForSaleQuery::create()
      ->joinWith('Collectible')
      ->limit($limit)
      ->addAscendingOrderByColumn('RAND()');

    return $q->find();
  }

  public function getRelatedCollections($limit = 5, &$rnd_flag = false)
  {
    $collections = CollectorCollectionPeer::getRelatedCollections($this, $limit);

    if ($limit != $found = count($collections))
    {
      $limit = $limit - $found;
      $sf_context = sfContext::getInstance();

      /** @var $sf_user cqUser */
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
   * @return integer | null
   */
  public function getCollectionId()
  {
    return null;
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

  /**
   * @param  PropelPDO  $con
   * @return Collection | CollectionDropbox
   */
  public function getCollection(PropelPDO $con = null)
  {
    /** @var $q CollectionQuery */
    $q = CollectionQuery::create()
       ->joinCollectionCollectible()
       ->useCollectionCollectibleQuery()
         ->filterByCollectible($this)
         ->filterByIsPrimary(true)
       ->endUse();

    if (!$collection = $q->findOne($con))
    {
      $collection = new CollectionDropbox($this->getCollectorId());
    }

    return $collection;
  }

  public function getCollectorCollection(PropelPDO $con = null)
  {
    return $this->getCollection($con);
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

  public function addMultimedia($file, $primary = false, $queue = false)
  {
    /**
     * @todo: We need to allow multimedia to be not primary and handle the case where there is already a primary
     *        multimedia for this collectible and when the first multimedia is always primary
     */
    if ($multimedia = MultimediaPeer::createMultimediaFromFile($this, $file))
    {
      $multimedia->setIsPrimary($primary);

      /**
       * We need to have the two main thumbnails available as soon as the object is saved so
       * we make sure they are not put on the job queue
       */
      $multimedia->makeThumb('150x150', 'shave', false);
      $multimedia->makeThumb('485x365', 'shave', false);
      $multimedia->makeThumb('420x1000', 'bestfit', false);

      // The rest of the thumnails are not immediately used so they can be deferred
      $multimedia->makeThumb('75x75', 'shave', $queue);
      $multimedia->makeThumb('1024x768', 'bestfit', $queue);

      // Here we want to create an optimized thumbnail for the homepage
      if ($multimedia->getOrientation() == 'landscape') {
        $multimedia->makeThumb('230x150', 'shave', $queue);
      } else {
        $multimedia->makeThumb('170x230', 'shave', $queue);
      }

      $multimedia->save();

      return $multimedia;
    }

    return null;
  }

  public function getMultimedia($primary = null, $type = 'image')
  {
    $c = new Criteria();
    $c->add(MultimediaPeer::MODEL, 'Collectible');
    $c->add(MultimediaPeer::MODEL_ID, $this->getId());
    $c->add(MultimediaPeer::TYPE, $type);

    if (is_bool($primary))
    {
      $c->add(MultimediaPeer::IS_PRIMARY, $primary);
    }

    return ($primary == true) ? MultimediaPeer::doSelectOne($c) : MultimediaPeer::doSelect($c);
  }

  public function hasMultimedia()
  {
    $c = new Criteria();
    $c->add(MultimediaPeer::MODEL, 'Collectible');
    $c->add(MultimediaPeer::MODEL_ID, $this->getId());

    return MultimediaPeer::doCount($c) > 0;
  }

  public function rotateMultimedia($is_primary = true, $queue = false)
  {
    $multimedia = $this->getMultimedia($is_primary);
    if ($multimedia && !is_array($multimedia))
    {
      $multimedia = array($multimedia);
    }

    /** @var $m Multimedia */
    foreach ($multimedia as $m)
    {
      $m->rotate('150x150', 90, false);
      $m->rotate('420x1000', 90, false);

      $m->rotate('75x75', 90, $queue);
      $m->rotate('1024x768', 90, $queue);
      $m->getOrientation() == 'landscape' ?
          $m->rotate('230x150', 90, $queue) :
          $m->rotate('170x230', 90, $queue);
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
   *
   * @return CollectibleForSale
   */
  public function getForSaleInformation()
  {
    $c = new Criteria();
    $c->add(CollectibleForSalePeer::COLLECTIBLE_ID, $this->getId());

    return CollectibleForSalePeer::doSelectOne($c);
  }

  /**
   * @param  null|PropelPDO  $con
   * @return boolean
   */
  public function preDelete(PropelPDO $con = null)
  {
    // Deleting collectibles for sale
    $collectibles_for_sale = $this->getCollectibleForSales();
    if (!empty($collectibles_for_sale))
    {
      /** @var $collectible_for_sale CollectibleForSale */
      foreach ($collectibles_for_sale as $collectible_for_sale)
      {
        $collectible_for_sale->delete($con);
      }
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
