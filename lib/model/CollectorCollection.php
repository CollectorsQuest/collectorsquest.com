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

  public function getLatestCollectibles($limit)
  {
    $c = new Criteria();
    $c->add(CollectionCollectiblePeer::COLLECTION_ID, $this->getId());
    $c->addDescendingOrderByColumn(CollectionCollectiblePeer::POSITION);
    $c->addDescendingOrderByColumn(CollectionCollectiblePeer::UPDATED_AT);
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

}

sfPropelBehavior::add('CollectorCollection', array('IceMultimediaBehavior'));
sfPropelBehavior::add('CollectorCollection', array('IceTaggableBehavior'));

sfPropelBehavior::add(
  'CollectorCollection',
  array('PropelActAsEblobBehavior' => array('column' => 'eblob')
));
