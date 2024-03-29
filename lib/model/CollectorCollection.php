<?php

/**
 * IceTaggableBehavior
 *
 * @method array getTags($options = array())
 * @method boolean setTags($names)
 * @method boolean addTag($name)
 * @method boolean hasTag($name)
 */
class CollectorCollection extends BaseCollectorCollection
{
  /* @var boolean */
  private $_tried_graph_id_creation = false;

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

  /**
   * @param     boolean $force Force attempt even if previously failed
   * @param     PropelPDO $con
   *
   * @return    integer|null
   */
  public function getGraphId($force = false, PropelPDO $con = null)
  {
    // if not new object and no graph id set
    if (
      !$this->isNew() &&
      null === parent::getGraphId()
      && (!$this->_tried_graph_id_creation || $force)
    )
    {
      // set flag to prevent infinite recursion on save
      $this->_tried_graph_id_creation = true;
      // try to create a new graph id for this object
      $this->setGraphId($this->createGraphId($con));
      $this->save($con);
    }

    return parent::getGraphId();
  }

  /**
   * Tries to create a new Neo4j graph id for this object.
   * Returns the graph id on success or null on failure.
   *
   * If a graph id already exists for this object it is returned directly
   *
   * @param     PropelPDO $con
   * @return    integer|null
   */
  protected function createGraphId(PropelPDO $con = null)
  {
    if (null !== parent::getGraphId())
    {
      return parent::getGraphId();
    }

    // try to create a new graph id
    try
    {
      $client = cqStatic::getNeo4jClient();
      $node = $client->makeNode();
      $node->setProperty('model', 'Collection');
      $node->setProperty('model_id', $this->getId());
      $node->save();

      $graph_id = $node->getId();
    }
    catch(Everyman\Neo4j\Exception $e)
    {
      return null;
    }

    // check if the graph id is unique
    return !CollectorCollectionQuery::create()
      ->filterByGraphId($graph_id)
      ->count($con)
      ? $graph_id
      : null;
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
   *
   * @return CollectorCollection
   */
  public function setDescription($v, $type = 'html')
  {
    if ('html' == $type)
    {
      $v = IceStatic::cleanText(
        $v, false,
        'p, b, u, i, em, strong, h3, h4, h5, h6, div, span, ul, ol, li, blockquote, br'
      );
      $v = str_replace('&nbsp;', ' ', $v);
    }

    // We should always save the description in Markdown format
    return parent::setDescription($v);
  }

  /**
   * @param  string  $type Can be 'html' or 'markdown'
   * @param  integer $limit Limit the number of characters returned,
   *                        Only honored if $type == 'stripped'
   *
   * @return string
   */
  public function getDescription($type = 'html', $limit = 0)
  {
    // By default the description is in Markdown format in the database
    $v = parent::getDescription();

    switch ($type)
    {
      case 'stripped':
        $v = trim(strip_tags($v));
        $v = (intval($limit) > 0) ? cqStatic::truncateText($v, $limit, '...', true) : $v;
        break;
      case 'html':
      default:
        $v = str_replace('&lt;a ', '&lt;a rel=&quot;nofollow&quot; ', $v);
        $v = str_replace('<a ', '<a rel="nofollow" ', $v);
        break;
    }

    return $v;
  }

  public function getLatestCollectibles($limit,$onlyPublic = false)
  {
    $c = new Criteria();
    $c->add(CollectionCollectiblePeer::COLLECTION_ID, $this->getId());
    $c->addDescendingOrderByColumn(CollectionCollectiblePeer::POSITION);
    $c->addDescendingOrderByColumn(CollectionCollectiblePeer::UPDATED_AT);
    if ($onlyPublic)
    {
      $c->addJoin(CollectionCollectiblePeer::COLLECTIBLE_ID, CollectiblePeer::ID, Criteria::LEFT_JOIN);
      $c->add(CollectiblePeer::IS_PUBLIC, true);
    }
    $c->setLimit($limit);

    return CollectionCollectiblePeer::doSelect($c);
  }

  public function setThumbnail($file)
  {
    return $this->setPrimaryImage($file);
  }

  public function hasThumbnail()
  {
    return $this->getPrimaryImage() ? true : false;
  }

  public function getThumbnail()
  {
    return $this->getPrimaryImage();
  }

  /**
   * Proxy method for ContentCategory::getPath()
   *
   * @param  string  $glue
   * @param  string  $column
   *
   * @return null|string
   */
  public function getCategoryPath($glue = ' / ', $column = 'Name')
  {
    if ($content_category = $this->getContentCategory())
    {
      return $content_category->getPath($glue, $column);
    }

    return null;
  }

  public function updateIsPublic(PropelPDO $con = null)
  {
    if ($con === null)
    {
      $con = Propel::getConnection(
        CollectorCollectionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE
      );
    }

    // Start with the current public status of the Collection
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
    else if ($is_public === true && !$this->getPrimaryImage(Propel::CONNECTION_WRITE))
    {
      $is_public = false;
    }

    // Update only if there is a change of the public status
    if ($is_public !== $this->getIsPublic())
    {
      $this->setIsPublic($is_public);

      $sql = sprintf(
        'UPDATE %s SET %s = %d WHERE %s = %d',
        CollectorCollectionPeer::TABLE_NAME, CollectorCollectionPeer::IS_PUBLIC, $is_public,
        CollectorCollectionPeer::ID, $this->getId()
      );
      $con->exec($sql);

      $sql = sprintf(
        'UPDATE %s SET %s = %d WHERE %s = %d',
        CollectionPeer::TABLE_NAME, CollectionPeer::IS_PUBLIC, $is_public,
        CollectionPeer::ID, $this->getId()
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
     * We need to have the two main thumbnails available as soon as the object is saved so
     * we make sure they are not put on the job queue
     */
    $multimedia->makeThumb(150, 150, 'top', $watermark);
    $multimedia->makeCustomThumb(50, 50, '50x50', 'top', false);
    $multimedia->makeCustomThumb(190, 150, '190x150', 'top', $watermark);
    $multimedia->makeCustomThumb(190, 190, '190x190', 'top', $watermark);
  }

  /**
   * @param PropelPDO $con
   * @return type
   */
  public function preDelete(PropelPDO $con = null)
  {
    CommentQuery::create()
      ->filterByModelObject($this)
      ->delete($con);

    return parent::preDelete($con);
  }

}

sfPropelBehavior::add('CollectorCollection', array('IceMultimediaBehavior'));
sfPropelBehavior::add('CollectorCollection', array('IceTaggableBehavior'));

sfPropelBehavior::add(
  'CollectorCollection',
  array('PropelActAsEblobBehavior' => array('column' => 'eblob')
));
