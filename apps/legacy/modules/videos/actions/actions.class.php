<?php

class videosActions extends cqActions
{
  /**
   * Executes index action
   *
   */
  public function executeIndex(sfWebRequest $request)
  {
    if (!$request->getParameter('playlist_id'))
    {
      $c = new Criteria;
      $c->add(PlaylistPeer::IS_PUBLISHED, true);
      $c->addAscendingOrderByColumn('RAND()');
      $playlist = PlaylistPeer::doSelectOne($c);

      return $this->redirect('@video_playlist?playlist_id='.$playlist->getId().'&slug='.$playlist->getSlug(), 301);
    }
    else
    {
      $playlist = PlaylistPeer::retrieveByPK($request->getParameter('playlist_id'));
    }
    $this->forward404Unless($playlist);

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
    $video = VideoPeer::retrieveByPK($request->getParameter('id'));
    $this->forward404unless($video);

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
    $this->amazon_products = cqStatic::getAmazonProducts(
      2, array_slice($video->getTags(), rand(0, count($video->getTags())), 2, true)
    );

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

  // vlist for the collectors videos
  public function executeVlist()
  {
    $playlist = PlaylistPeer::retrieveByPK($this->getRequestParameter('playlist_id'));
    $this->forward404Unless($playlist);

    $pager = $playlist->getPublishedVideosPager($this->getRequestParameter('page', 1), sfConfig::get('app_pager_video_max'));

    $this->pager = $pager;
    $this->videos = $pager->getResults();
    $this->playlist = $playlist;
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

  public function executeSitemap()
  {
    $sitemap = sfSitemapPeer::newInstance('default');

    $item = new sfSitemapItem();
    $item->initialize(
      array(
        'loc' => 'videos/index',
        'changeFreq' => 'daily',
        'priority' => 0.5
      )
    );
    $sitemap->addItem($item);

    $c = new Criteria();
    $c->addSelectColumn(VideoPeer::ID);
    $c->addSelectColumn(VideoPeer::SLUG);
    $c->addSelectColumn(VideoPeer::PUBLISHED_AT);
    $c->add(VideoPeer::IS_PUBLISHED, true);
    $c->addDescendingOrderByColumn(VideoPeer::ID);

    $stmt = VideoPeer::doSelectStmt($c);
    while ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      $item = new sfSitemapItem();
      $item->initialize(
        array(
          'loc' => sprintf('@video_by_slug?id=%d&slug=%s', $row[0], $row[1]),
          'lastMod' => strtotime($row[2]),
          'changeFreq' => 'weekly',
          'priority' => 0.1
        )
      );
      $sitemap->addItem($item);
    }

    $this->renderText($sitemap->asXml());

    return sfView::NONE;
  }
}

