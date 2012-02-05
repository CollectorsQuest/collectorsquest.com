<?php

class ajaxActions extends cqActions
{
  public function executeEditable(sfWebRequest $request)
  {
    @list($model, $id, $field) = explode('_', $request->getParameter('id'));
    $value = $request->getParameter('value');

    if ($model && is_callable(array(sfInflector::camelize($model).'Peer', 'retrieveByPk')))
    {
      // Cast the id to INT for sanity
      $id = (int) $id;

      $object = call_user_func_array(array(sfInflector::camelize($model).'Peer', 'retrieveByPk'), array($id));
      if ($object && is_callable(array($object, 'set'. sfInflector::camelize($field))))
      {
        if ($this->getCollector()->isOwnerOf($object) || true)
        {
          call_user_func_array(array($object, 'set'. sfInflector::camelize($field)), array($value));
          $object->save();
        }
      }

      if (is_callable(array($object, 'get'. sfInflector::camelize($field))))
      {
        $value = call_user_func(array($object, 'get'. sfInflector::camelize($field)));
      }
    }

    $this->renderText($value);

    return sfView::NONE;
  }

  public function executeEditableLoad(sfWebRequest $request)
  {
    @list($model, $id, $field) = explode('_', $request->getParameter('id'));
    $value = '';

    if ($model && is_callable(array(sfInflector::camelize($model).'Peer', 'retrieveByPk')))
    {
      // Cast the id to INT for sanity
      $id = (int) $id;

      $object = call_user_func_array(array(sfInflector::camelize($model).'Peer', 'retrieveByPk'), array($id));

      if (is_callable(array($object, 'get'. sfInflector::camelize($field))))
      {
        if (in_array($field, array('name', 'description')))
        {
          $value = call_user_func_array(array($object, 'get'. sfInflector::camelize($field)), array('markdown'));
        }
        else
        {
          $value = call_user_func(array($object, 'get'. sfInflector::camelize($field)));
        }
      }
    }

    $this->renderText($value);

    return sfView::NONE;
  }

  public function executeCollectionSnapshot()
  {
    $collection = $this->getRoute()->getObject();
    $this->collectibles = $collection->getRandomCollectibles($this->getRequestParameter('collectibles', 3));

    return sfView::SUCCESS;
  }

  public function executeVideoPlaylistXml()
  {
    $playlist = PlaylistPeer::retrieveByPK($this->getRequestParameter('id'));
    $this->forward404Unless($playlist);

    $this->getResponse()->setContentType('application/xml');
    echo $playlist->toXml();

    return sfView::HEADER_ONLY;
  }

  public function executeSingleVideoXml(sfWebRequest $request)
  {
    $video = VideoPeer::retrieveByPK($request->getParameter('id'));
    $this->forward404Unless($video);

    $playlist = simplexml_load_string('<playlist version="1" xmlns="http://xspf.org/ns/0/"></playlist>');
    $playlist->addChild('title', htmlentities($video->getTitle()));
    $playlist->addChild('info', htmlentities($video->getDescription()));
    $tracklist = $playlist->addChild('trackList');

    $track = $tracklist->addChild('track');
    $track->addChild('title', htmlentities($video->getTitle()));
    $track->addChild('creator', 'CollectorsQuest.com');
    $track->addChild('annotation', htmlentities($video->getDescription()));
    $track->addChild('location', 'http://www.collectorsquest.com/uploads/videos'. $video->getFilenameSrc());
    $track->addChild('image', 'http://www.collectorsquest.com/uploads/videos'. $video->getThumbLargeSrc());
    $track->addChild('info', 'http://'. sfConfig::get('app_domain_name'));

    $videos = $video->getTagRelatedVideos(4);
    if (empty($videos))
    {
      $videos = $video->getLooselyRelatedVideos(4);
    }

    foreach ($videos as $video)
    {
      $track = $tracklist->addChild('track');
      $track->addChild('title', $video->getTitle());
      $track->addChild('creator', 'CollectorsQuest.com');
      $track->addChild('annotation', $video->getDescription());
      $track->addChild('location', 'http://www.collectorsquest.com/uploads/videos'. $video->getFilenameSrc(true));
      $track->addChild('image', 'http://www.collectorsquest.com/uploads/videos'. $video->getThumbLargeSrc(true));
      $track->addChild('date', $video->getPublishedAt());
      $track->addChild('info', 'http://'. sfConfig::get('app_domain_name'));
    }

    $this->getResponse()->setContentType('application/xml');
    echo $playlist->asXml();

    return sfView::HEADER_ONLY;
  }

  public function executeLogin()
  {
    $this->form = new CollectorLoginForm();
    $this->rpxnow = sfConfig::get('app_credentials_rpxnow');

    return sfView::SUCCESS;
  }

  public function executeSignupChoice()
  {
    return sfView::SUCCESS;
  }
}
