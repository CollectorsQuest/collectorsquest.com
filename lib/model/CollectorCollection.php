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
   *
   * @return string
   */
  public function getDescription($type = 'html')
  {
    // By default the description is in Markdown format in the database
    $v = parent::getDescription();

    switch ($type)
    {
      case 'stripped':
        $v = trim(strip_tags($v));
        break;
      case 'html':
        $v = cqMarkdown::doConvert($v);
        break;
    }

    return $v;
  }

  public function setThumbnail($file)
  {
    /** @var $multimedia Multimedia */
    $multimedia = parent::setThumbnail($file);

    if ($multimedia && !$this->getCollector()->hasPhoto())
    {
      $this->getCollector()->setPhoto($multimedia->getAbsolutePath('original'));
    }

    return $multimedia;
  }

}
