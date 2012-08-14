<?php

class cqPropelModelPager extends PropelModelPager
{
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
