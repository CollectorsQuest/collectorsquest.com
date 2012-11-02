<?php

class cqPropelModelPager extends PropelModelPager
{
  /**
   * @var bool
   */
  private $strictMode = false;

  /**
   * @param bool $strictMode
   */
  public function setStrictMode($strictMode)
  {
    $this->strictMode = $strictMode;
  }

  /**
   * @return bool
   */
  public function getStrictMode()
  {
    return $this->strictMode;
  }

  /**
   * Sets the last page number.
   *
   * @param integer $page
   */
  protected function setLastPage($page)
  {
    $this->lastPage = $page;

    if ($this->getStrictMode() && $this->getPage() > $page)
    {
      $this->setPage($page);
    }
  }

  public function asRssFeed($route, sfRssFeed $feed = null)
  {
    $feed = $feed ?: new sfRssFeed();

    if (!$feed->getTitle())
    {
      $feed->initialize(array(
        'title'       => 'Collectors Quest',
        'link'        => 'http://www.collectorsquest.com/',
        'authorEmail' => 'info@collectorsquest.com',
        'authorName'  => 'Collectors Quest, Inc'
      ));
    }

    $items = sfFeedPeer::convertObjectsToItems(
      $this->getResults(), array('routeName' => $route)
    );
    $feed->addItems($items);

    return $feed->asXml(ESC_RAW);
  }
}
