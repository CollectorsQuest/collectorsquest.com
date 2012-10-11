<?php

require 'lib/model/om/BaseCollectorCollection.php';

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
   * Proxy method for CollectionCategory::getPath()
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
   * Return triple tags according to a namespace
   *
   * @param     string $ns
   * @param     string $key
   * @return    array
   */
  protected function getNamespacedTags($ns, $key)
  {
    return $this->getTags(array(
      'is_triple' => true,
      'namespace' => $ns,
      'key'       => $key,
      'return'    => 'value',
    ));
  }

  /**
   * Add a tag or tags for a namespace
   *
   * @param     string|array $tagname Anything that ::addTag() accepts
   * @param     string $ns
   * @param     string $key
   *
   * @return void
   */
  protected function addNamespacedTag($tagname, $ns, $key)
  {
    $tags = (array) IceTaggableToolkit::explodeTagString($tagname);

    $triple_prefix = sprintf('%s:%s=', $ns, $key);

    array_walk($tags, function(&$tag) use ($triple_prefix)
    {
      if (0 !== strpos($tag, $triple_prefix))
      {
        $tag = $triple_prefix.$tag;
      }
    });

    $this->addTag($tags);
  }

  /**
   * Set the tags for a specific namespace
   *
   * @param     string|array $tags Anything that ::addTag() accepts
   * @param     string $ns
   * @param     string $key
   *
   * @return    void
   */
  protected function setNamespacedTags($tags, $ns, $key)
  {
    $this->removeAllNamespacedTags($ns, $key);
    $this->addNamespacedTag($tags, $ns, $key);
  }

  /**
   * Remove all tags for a specific namespace
   *
   * @param     string  $ns
   * @param     string  $key
   *
   * @return    void
   */
  protected function removeAllNamespacedTags($ns, $key)
  {
    $this->removeTag($this->getTags(array(
      'is_triple' => true,
      'namespace' => $ns,
      'key'       => $key,
      'return'    => 'tag',
    )));
  }

  /**
   * Return tags for the INTERNAL namespace
   *
   * @return    array
   */
  public function getInternalTags()
  {
    return $this->getNamespacedTags(
      CollectorCollectionPeer::TAGS_NAMESPACE_INTERNAL,
      CollectorCollectionPeer::TAGS_KEY_TAG
    );
  }

  /**
   * Add tag or tags to the INTERNAL namespace
   *
   * @param     string|array $tagname Anything that ::addTag() accepts
   */
  public function addInternalTag($tagname)
  {
    $this->addNamespacedTag(
      $tagname,
      CollectorCollectionPeer::TAGS_NAMESPACE_INTERNAL,
      CollectorCollectionPeer::TAGS_KEY_TAG
    );
  }

  /**
   * Remove all tags for the INTERNAL namespace
   */
  public function removeAllInternalTags()
  {
    $this->removeAllNamespacedTags(
      CollectorCollectionPeer::TAGS_NAMESPACE_INTERNAL,
      CollectorCollectionPeer::TAGS_KEY_TAG
    );
  }

  /**
   * Set the tags for the INTERNAL namespace
   *
   * @param     string|array $tags Anything that ::addTag() accepts
   */
  public function setInternalTags($tags)
  {
    $this->setNamespacedTags(
      $tags,
      CollectorCollectionPeer::TAGS_NAMESPACE_INTERNAL,
      CollectorCollectionPeer::TAGS_KEY_TAG
    );
  }

  /**
   * Return a string representation of the Internal tags
   *
   * @param     string $glue
   * @return    string
   */
  public function getInternal($glue = ', ')
  {
    return implode($glue, $this->getInternalTags());
  }

  /**
   * Return array of tags, not triple by default
   *
   * @param array $params
   * @return array
   */
  public function getTags($params = array('is_triple' => false))
  {
    return parent::getTags($params);
  }

  /**
   * Set tags
   * by default it set only not triple tags
   *
   * @param $tags
   * @param bool $all set all object tags
   * @return mixed
   */
  public function setTags($tags, $all = false)
  {
    if (!$all)
    {
      $this->removeTag($this->getTags(array(
        'is_triple' => false,
      )));
      return $this->addTag($tags);
    }
    else
    {
      return parent::setTags($tags);
    }
  }

}

sfPropelBehavior::add('CollectorCollection', array('IceMultimediaBehavior'));
sfPropelBehavior::add('CollectorCollection', array('IceTaggableBehavior'));

sfPropelBehavior::add(
  'CollectorCollection',
  array('PropelActAsEblobBehavior' => array('column' => 'eblob')
));
