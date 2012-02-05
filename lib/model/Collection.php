<?php

class Collection extends BaseCollection
{
  /**
   * @return string
   */
  public function __toString()
  {
	return $this->getName();
  }

  public function getGraphId()
  {
    $graph_id = null;

    if (!$this->isNew() && (!$graph_id = parent::getGraphId()))
    {
      $client = cqStatic::getNeo4jClient();

      $node = $client->makeNode();
      $node->setProperty('model', 'Collection');
      $node->setProperty('model_id', $this->getId());
      $node->save();

      $graph_id = $node->getId();

      $this->setGraphId($node->getId());
      $this->save();
    }

    return $graph_id;
  }

  /**
   * @param  string $v
   * @return void
   */
  public function setName($v)
  {
    parent::setName(IceStatic::cleanText($v, false, 'none'));
  }

  /**
   * Set the description of the collection
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

  /**
   * @param  string  $type Can be 'html' or 'markdown'
   *
   * @return string
   */
  public function getDescription($type = 'html')
  {
    $v = parent::getDescription();

    switch ($type)
    {
      case 'stripped':
        $v = trim(strip_tags($v));
        break;
      case 'html':
      default:
        $v = cqMarkdown::doConvert($v);
        break;
    }

    return $v;
  }

  public function getTagString()
  {
    return implode(", ", $this->getTags());
  }

  public function getTagIds()
  {
    $c = new Criteria;
    $c->addSelectColumn(iceModelTaggingPeer::TAG_ID);
    $c->add(iceModelTaggingPeer::TAGGABLE_ID, $this->getId());
    $c->add(iceModelTaggingPeer::TAGGABLE_MODEL, 'Collection');
    $stmt = iceModelTaggingPeer::doSelectStmt($c);

    return $stmt->fetchAll(PDO::FETCH_COLUMN);
  }

  public function getCollectibleIds()
  {
    $c = new Criteria();
    $c->addSelectColumn(CollectiblePeer::ID);
    $c->add(CollectiblePeer::COLLECTION_ID, $this->getId());
    $c->addAscendingOrderByColumn(CollectiblePeer::POSITION);
    $c->addAscendingOrderByColumn(CollectiblePeer::CREATED_AT);
    $stmt = CollectiblePeer::doSelectStmt($c);

    return $stmt->fetchAll(PDO::FETCH_COLUMN);
  }

  public function getCollectibles($criteria = null, PropelPDO $con = null)
  {
    $c = ($criteria instanceof Criteria) ? clone $criteria : new Criteria();

    $c->addAscendingOrderByColumn(CollectiblePeer::POSITION);
    $c->addDescendingOrderByColumn(CollectiblePeer::CREATED_AT);

    return parent::getCollectibles($c, $con);
  }

  public function getRandomCollectibles($limit = 10)
  {
    $c = new Criteria();
    $c->add(CollectiblePeer::COLLECTION_ID, $this->getId());
    $c->setLimit($limit);
    $c->addAscendingOrderByColumn('RAND()');

    return CollectiblePeer::doSelect($c);
  }

  public function getRelatedCollections($limit = 5)
  {
    $collections = CollectionPeer::getRelatedCollections($this, $limit);

    if ($limit != $found = count($collections))
    {
      $limit = $limit - $found;
      $context = sfContext::getInstance();

      if ($context && $context->getUser()->isAuthenticated())
      {
        $collector = $context->getUser()->getCollector();

        $c = new Criteria();
        $c->add(CollectionPeer::ID, $this->getId(), Criteria::NOT_EQUAL);
        $c->add(CollectionPeer::COLLECTOR_ID, $collector->getId(), Criteria::NOT_EQUAL);
        $c->addAscendingOrderByColumn('RAND()');

        $collections = array_merge($collections, CollectionPeer::getRelatedCollections($collector, $limit, $c));
      }
    }

    if (0 == count($collections))
    {
      $c = new Criteria();
      $c->add(CollectionPeer::ID, $this->getId(), Criteria::NOT_EQUAL);

      $collections = CollectionPeer::getRandomCollections($limit, $c);
      $rnd_flag = true;
    }

    return $collections;
  }

  public function hasThumbnail()
  {
    return MultimediaPeer::has($this, 'image', true);
  }

  public function getThumbnail()
  {
    return MultimediaPeer::get($this, 'image', true);
  }

  public function setThumbnail($file)
  {
    $c = new Criteria();
    $c->add(MultimediaPeer::MODEL, 'Collection');
    $c->add(MultimediaPeer::MODEL_ID, $this->getId());
    $c->add(MultimediaPeer::TYPE, 'image');
    $c->add(MultimediaPeer::IS_PRIMARY, true);

    MultimediaPeer::doDelete($c);

    if ($multimedia = MultimediaPeer::createMultimediaFromFile($this, $file))
    {
      $multimedia->setIsPrimary(true);
      $multimedia->makeThumb('150x150', 'shave');
      $multimedia->makeThumb('50x50', 'shave');
      $multimedia->save();

      // Set a photo for the collector from the collection thumbnail
      if (!$this->getCollector()->hasPhoto())
      {
        $this->getCollector()->setPhoto($multimedia->getAbsolutePath('original'));
      }

      return true;
    }

    return false;
  }

  public function getCountCollectibles()
  {
    return $this->countCollectibles();
  }

  public function countCollectiblesSince($date = null)
  {
    $date = (is_null($date)) ? new DateTime('7 day ago') : new DateTime($date);

    $c = new Criteria();
    $c->add(CollectiblePeer::COLLECTION_ID, $this->getId());
    $c->add(CollectiblePeer::CREATED_AT, $date, Criteria::GREATER_EQUAL);

    return CollectiblePeer::doCount($c);
  }

  /**
   * @static
   * @param  integer $snIdCollector
   *
   * @return PDOStatement
   */
	public static function getCollectionAsPerCollector($snIdCollector)
	{
		$oCriteria = new Criteria();
		$oCriteria->addSelectColumn(CollectionPeer::ID);
		$oCriteria->addSelectColumn(CollectionPeer::NAME);
		$oCriteria->add(CollectionPeer::COLLECTOR_ID, $snIdCollector);
		$oCriteria->addAscendingOrderByColumn(CollectionPeer::NAME);

		return CollectionPeer::doSelectStmt($oCriteria);
	}

  /**
   * @param null|PropelPDO $con
   *
   * @return boolean
   */
  public function preDelete(PropelPDO $con = null)
  {
    /** @var $collectibles Collectible[] */
    if ($collectibles = $this->getCollectibles())
    foreach ($collectibles as $collectible)
    {
      $collectible->delete($con);
    }

    /** @var $comments Comment[] */
    if ($comments = $this->getComments())
    foreach ($comments as $comment)
    {
      $comment->delete($con);
    }

    return parent::preDelete($con);
  }
}

sfPropelBehavior::add('Collection', array('IceTaggableBehavior'));

sfPropelBehavior::add(
  'Collection',
  array('PropelActAsEblobBehavior' => array('column' => 'eblob')
));

sfPropelBehavior::add(
  'Collection',
  array(
    'PropelActAsSluggableBehavior' => array(
      'columns' => array(
        'from' => CollectionPeer::NAME,
        'to' => CollectionPeer::SLUG
      ),
      'separator' => '-',
      'permanent' => false,
      'lowercase' => true,
      'ascii' => true,
      'chars' => 128
    )
  )
);
