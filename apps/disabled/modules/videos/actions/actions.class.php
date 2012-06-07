<?php

class videosActions extends cqActions
{

  public function executeIndex(sfWebRequest $request)
  {
    if (!$request->getParameter('id'))
    {
      $c = new Criteria;
      $c->add(PlaylistPeer::IS_PUBLISHED, true);
      $c->addAscendingOrderByColumn('RAND()');
      $playlist = PlaylistPeer::doSelectOne($c);

      return $this->redirect('@video_playlist?id='. $playlist->getId() .'&slug='. $playlist->getSlug(), 301);
    }
    else
    {
      /** @var $playlist Playlist */
      $playlist = $this->getRoute()->getObject();
      $this->forward404Unless($playlist->getIsPublished());
    }

    $this->videos = $playlist->getPublishedVideos();
    $this->playlist = $playlist;
    $this->playlist_xml = '@ajax_video_playlist_xml?id='. $playlist->getId();

    $this->playlists = array();

    $c = new Criteria();
    $c->add(PlaylistPeer::IS_PUBLISHED, true);
    $c->add(PlaylistPeer::TYPE, 'event');
    $c->setLimit(4);
    $c->addAscendingOrderByColumn('RAND()');
    $this->playlists['events']  = PlaylistPeer::doSelect($c);

    $c->setLimit(0);
    $c->add(PlaylistPeer::TYPE, 'spotlight');
    $c->addAscendingOrderByColumn('RAND()');
    $this->playlists['spotlight']  = PlaylistPeer::doSelect($c);

    $this->prependTitle($this->__('Video Playlist'));
    $this->prependTitle($playlist->getTitle());

    $this->addBreadcrumb($this->__('Videos'), '@videos');
    $this->addBreadcrumb($playlist->getTitle());

    return sfView::SUCCESS;
  }

  public function executeSingleVideo(sfWebRequest $request)
  {
    /** @var $video Video */
    $video = $this->getRoute()->getObject();

    $c = new Criteria();
    $c->add(PlaylistPeer::IS_PUBLISHED, true);
    $c->addDescendingOrderByColumn(PlaylistPeer::PUBLISHED_AT);
    $this->playlists  = PlaylistPeer::doSelect($c);

    $videos = $video->getTagRelatedVideos(4);
    if (empty($videos))
    {
      $videos = $video->getLooselyRelatedVideos(4);
    }

    $this->videos = array_merge(array($video), $videos);
    $this->video = $video;
    $this->playlist_xml = '@ajax_video_single_xml?id='. $video->getId();

    // $this->amazon_products = cqStatic::getAmazonProducts(
    //   2, array_slice($video->getTags(), rand(0, count($video->getTags())), 2, true)
    // );

    $this->playlists = array();

    $c = new Criteria();
    $c->add(PlaylistPeer::IS_PUBLISHED, true);
    $c->add(PlaylistPeer::TYPE, 'event');
    $c->setLimit(4);
    $c->addDescendingOrderByColumn(PlaylistPeer::PUBLISHED_AT);
    $this->playlists['events']  = PlaylistPeer::doSelect($c);

    $c->add(PlaylistPeer::TYPE, 'spotlight');
    $this->playlists['spotlight']  = PlaylistPeer::doSelect($c);

    $this->getResponse()->addMeta('description', $video->getDescription());
    $this->getResponse()->addMeta('keywords', implode(', ', $video->getTags()));

    $this->prependTitle('Video');
    $this->prependTitle($video->getTitle());

    $this->addBreadcrumb($this->__('Videos'), '@videos');
    $this->addBreadcrumb($video->getTitle());

    $this->setTemplate('index');

    return sfView::SUCCESS;
  }

  public function executeEvents()
  {
    $c = new Criteria();
    $c->addAscendingOrderByColumn(EventVideoPeer::CREATED_AT);
    $c->setLimit(5);

    $pager = new sfPropelPager('EventVideo', sfConfig::get('app_pager_video_max'));
    $pager->setCriteria($c);
    $pager->setPage($this->getRequestParameter('page', 1));
    $pager->init();

    $this->area = 'content';
    $this->video_pager = $pager;
  }

}
