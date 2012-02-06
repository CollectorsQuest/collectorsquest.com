<?php

class Playlist extends BasePlaylist
{
  public function __toString()
  {
    return (string) $this->getTitle();
  }

  public function toXml()
  {
    $playlist = simplexml_load_string('<playlist version="1" xmlns="http://xspf.org/ns/0/"></playlist>');
    $playlist->addChild('title', htmlentities($this->getTitle()));
    $playlist->addChild('info', htmlentities($this->getDescription()));
    $tracklist = $playlist->addChild('tracklist');

    $videos = $this->getPublishedVideos();
    foreach ($videos as $video)
    {
      $track = $tracklist->addChild('track');
      $track->addChild('title', htmlentities($video->getTitle()));
      $track->addChild('creator', 'CollectorsQuest.com');
      $track->addChild('annotation', htmlentities($video->getDescription()));
      $track->addChild('location', 'http://www.collectorsquest.com/uploads/videos'. $video->getFilenameSrc());
      $track->addChild('image', 'http://www.collectorsquest.com/uploads/videos'. $video->getThumbLargeSrc(true));
      $track->addChild('date', $video->getPublishedAt());
      $track->addChild('info', 'http://'. sfConfig::get('app_domain_name'));
    }

    return $playlist->asXML();
  }

  public function getVideoPlaylistPager($page, $per_page = 10)
  {
    $c = new Criteria();
    $c->add(VideoPlaylistPeer::PLAYLIST_ID, $this->getId());
    $c->addDescendingOrderByColumn(VideoPeer::CREATED_AT);

    $pager = new sfPropelPager('VideoPlaylist', $per_page);
    $pager->setPeerMethod('doSelectJoinVideo');
    $pager->setPeerCountMethod('doCountJoinVideo');
    $pager->setCriteria($c);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }

  public function getCountVideos()
  {
    return $this->countVideoPlaylists();
  }

  public function getPublished()
  {
    if ($this->getIsPublished())
    {
      return $this->getPublishedAt('m/d/y');
    }
    else
    {
      return 'Not published';
    }
  }

  public function getPublishedVideosPager($page, $per_page = 10)
  {
    $c = new Criteria();
    $c->add(VideoPeer::IS_PUBLISHED, true);
    $c->addJoin(VideoPeer::ID, VideoPlaylistPeer::VIDEO_ID);
    $c->add(VideoPlaylistPeer::PLAYLIST_ID, $this->getId());
    $c->addDescendingOrderByColumn(VideoPeer::CREATED_AT);

    $pager = new sfPropelPager('Video', $per_page);
    $pager->setCriteria($c);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }

  public function getPublishedVideos($limit = 0)
  {
    $c = new Criteria();
    $c->add(VideoPeer::IS_PUBLISHED, true);
    $c->addJoin(VideoPeer::ID, VideoPlaylistPeer::VIDEO_ID);
    $c->add(VideoPlaylistPeer::PLAYLIST_ID, $this->getId());
    $c->addDescendingOrderByColumn(VideoPeer::CREATED_AT);
    $c->setLimit($limit);

    return VideoPeer::doSelect($c);
  }

  public function delete(PropelPDO $con = null)
  {
    $this->deleteVideoPlaylists();
    parent::delete($con);
  }

  public function deleteVideoPlaylists()
  {
    $c = new Criteria();
    $c->add(VideoPlaylistPeer::PLAYLIST_ID, $this->getId());
    return VideoPlaylistPeer::doDelete($c);
  }

  public function getThumbSmallSrc()
  {
    $c = new Criteria();
    $c->add(VideoPeer::IS_PUBLISHED, true);
    $c->addJoin(VideoPeer::ID, VideoPlaylistPeer::VIDEO_ID);
    $c->add(VideoPlaylistPeer::PLAYLIST_ID, $this->getId());
    $c->addDescendingOrderByColumn('RAND()');
    $video = VideoPeer::doSelectOne($c);

    return $video->getThumbSmallSrc();
  }
}

sfPropelBehavior::add(
  'Playlist',
  array(
    'PropelActAsSluggableBehavior' => array(
      'columns' => array(
        'from' => PlaylistPeer::TITLE,
        'to' => PlaylistPeer::SLUG
      ),
      'separator' => '-',
      'permanent' => false,
      'lowercase' => true,
      'ascii' => true,
      'chars' => 64
    )
  )
);
