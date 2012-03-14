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
    if ('html' == $type)
    {
      $v = IceStatic::cleanText($v, false, 'p, b, u, i, em, strong, h1, h2, h3, h4, h5, h6, div, span, ul, ol, li, blockquote');
      $v = cqMarkdownify::doConvert($v);
    }

    // We should always save the description in Markdown format
    parent::setDescription($v);
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
        $v = ($limit > 0) ? cqStatic::truncateText($v, $limit, true, '...') : $v;
        break;
      case 'html':
        $v = cqMarkdown::doConvert($v);
        break;
    }

    return $v;
  }

  public function setThumbnail($file)
  {
    $c = new Criteria();
    $c->add(MultimediaPeer::MODEL, 'CollectorCollection');
    $c->add(MultimediaPeer::MODEL_ID, $this->getId());
    $c->add(MultimediaPeer::TYPE, 'image');
    $c->add(MultimediaPeer::IS_PRIMARY, true);

    MultimediaPeer::doDelete($c);

    /** @var $multimedia Multimedia */
    if ($multimedia = MultimediaPeer::createMultimediaFromFile($this, $file))
    {
      $multimedia->setIsPrimary(true);
      $multimedia->makeThumb('150x150', 'shave');
      $multimedia->makeThumb('50x50', 'shave');
      $multimedia->save();

      if (!$this->getCollector()->hasPhoto())
      {
        $this->getCollector()->setPhoto($multimedia->getAbsolutePath('original'));
      }
    }

    return $multimedia;
  }

}
