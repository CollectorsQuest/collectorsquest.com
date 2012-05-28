<?php

class ajaxAction extends IceAjaxAction
{
  protected function getObject(sfWebRequest $request)
  {
    return $this->getUser()->getCollector();
  }

  /**
   * @param  sfWebRequest  $request
   * @return mixed
   */
  public function execute($request)
  {
    $this->collector = $this->getObject($request);

    // Stop here if there is no valid Collector or the Collector object is not saved yet
    $this->forward404if(!$this->collector || $this->collector->isNew());

    return parent::execute($request);
  }

  /**
   * @param  sfWebRequest $request
   * @return string
   */
  protected function executeCollectiblesUpload(sfWebRequest $request)
  {
    /** @var $collector Collector */
    $collector = $this->getUser()->getCollector();

    if ($this->collection instanceof Collection)
    {
      $this->forward404Unless($collector && $collector->isOwnerOf($this->collection));
    }

    // We will return an array (in JSON format)
    $output = array();

    if ($request->isMethod('post') && ($files = $request->getFiles('files')))
    {
      $this->loadHelpers('cqImages');

      foreach ($files as $file)
      {
        $name = preg_replace('/\.(jpg|jpeg|png|gif)$/iu', '', $file['name']);
        $name = mb_substr(str_replace(array('_', '-'), ' ', ucfirst($name)), 0, 64, 'utf8');

        try
        {
          $collectible = new Collectible();
          $collectible->setCollector($collector);
          $collectible->setName($name, true);
          $collectible->setBatchHash($request->getParameter('batch', null));
          $collectible->save();

          // Set the Collection after the collectible has been saved
          $collectible->setCollection($this->collection);

          /**
           * Add the image
           *
           * @var $multimedia iceModelMultimedia
           */
          if ($multimedia = $collectible->setThumbnail($file['tmp_name'], true))
          {
            $multimedia->setName($name);
            $multimedia->save();

            $output[] = array(
              'name' => $multimedia->getName(),
              'size' => $multimedia->getFileSize(),
              'type' => 'image/jpeg',
              'thumbnail' => src_tag_multimedia($multimedia, '19:15x60')
            );
          }
        }
        catch (PropelException $e)
        {
          $this->error($e->getCode(), $e->getMessage(), false);
          return sfView::NONE;
        }
      }
    }

    // We do not want the web debug bar on these requests
    sfConfig::set('sf_web_debug', false);

    return $this->output($output);
  }

  /**
   * @param  sfWebRequest $request
   * @return string
   */
  protected function executeCollectiblesReorder(sfWebRequest $request)
  {
    $collector = $this->getUser()->getCollector();

    if ($this->collection instanceof Collection)
    {
      $this->forward404Unless($collector && $collector->isOwnerOf($this->collection));
    }

    $items = $request->getParameter('items');
    $key   = $request->getParameter('key');
    parse_str($items, $order);

    if (isset($order[$key]) && is_array($order[$key]))
    {
      $pks = array_values($order[$key]);

      /** @var $q CollectionCollectibleQuery */
      $q = CollectionCollectibleQuery::create()
        ->filterByCollection($this->collection)
        ->filterByCollectibleId($pks, Criteria::IN);

      /** @var $collectibles CollectionCollectible[] */
      $collectibles = $q->find();

      foreach ($collectibles as $collectible)
      {
        foreach ($order[$key] as $position => $pk)
        {
          if ($collectible->getCollectibleId() == $pk && $collectible->getPosition() != $position)
          {
            $collectible->setPosition($position);
            $collectible->save();

            break;
          }
        }
      }
    }

    // We do not want the web debug bar on these requests
    sfConfig::set('sf_web_debug', false);

    return sfView::NONE;
  }

  /**
   * @return string
   */
  protected function executeCollectibleRotate()
  {
    $this->forward404Unless($this->collectible);

    // Do the rotate
    $this->collectible->rotateMultimedia(true, true);

    // We do not want the web debug bar on these requests
    sfConfig::set('sf_web_debug', false);

    return sfView::NONE;
  }

  /**
   * @return string
   */
  protected function executeCollectibleDonateImage(sfWebRequest $request)
  {
    /** @var $recipient Collectible */
    $recipient = CollectibleQuery::create()
      ->findOneById($this->getRequestParameter('recipient_id'));
    $this->forward404Unless($this->getUser()->isOwnerOf($recipient));

    /** @var $donor Collectible */
    $donor = CollectibleQuery::create()
      ->findOneById($this->getRequestParameter('donor_id'));
    $this->forward404Unless($this->getUser()->isOwnerOf($donor));

    /**
     * Only dropbox Collectibles can be image donors
     * because the donor gets killed (deleted) after donating
     */
    $this->forward404if($donor->countCollectionCollectibles() > 0);

    /** @var $image iceModelMultimedia */
    if ($image = $donor->getPrimaryImage(Propel::CONNECTION_WRITE))
    {
      $is_primary = (boolean) $request->getParameter(
        'is_primary', $recipient->getMultimediaCount('image') === 0
      );

      // Get rid of the old primary Multimedia
      if ($is_primary === true && ($primary = $recipient->getPrimaryImage()))
      {
        $primary->delete();
      }

      try
      {
        $image->setIsPrimary($is_primary);
        $image->setModelId($recipient->getId());
        $image->save();
      }
      catch (PropelException $e)
      {
        if (preg_match('/multimedia_U_1/i', $e->getMessage()))
        {
          return $this->error(
            'Multimedia Exists', 'This multimedia already exists for this object'
          );
        }

        throw $e;
      }

      $recipient->setUpdatedAt(time());
      $recipient->save();

      // Delete the $donor, not needed anymore
      $donor->delete();

      // Return "Success"
      $this->success();
    }
    else
    {
      $this->error('Error', 'There was a problem donating the image');
    }

    // We do not want the web debug bar on these requests
    sfConfig::set('sf_web_debug', false);

    return sfView::NONE;
  }

  /**
   * @return string
   */
  protected function executeCollectibleDelete()
  {
    /** @var $collectible Collectible */
    $collectible = CollectibleQuery::create()
      ->findOneById($this->getRequestParameter('collectible_id'));
    $this->forward404Unless($collectible instanceof Collectible);

    $collection = CollectorCollectionQuery::create()
      ->findOneById($this->getRequestParameter('collection_id'));

    if ($collection && $collection instanceof Collection)
    {
      $q = CollectionCollectibleQuery::create()
        ->filterByCollectionId($this->collection->getId())
        ->filterByCollectibleId($this->collectible->getId());

      // Do delete the reference in CollectionCollectible only
      $q->delete();
    }
    else
    {
      // Do the delete of the actual Collectible
      $collectible->delete();
    }

    // We do not want the web debug bar on these requests
    sfConfig::set('sf_web_debug', false);

    return $this->success();
  }

  /**
   * @return string
   */
  protected function executeCollectionSetThumbnail(sfWebRequest $request)
  {
    $collection = CollectorCollectionQuery::create()
      ->findOneById($request->getParameter('collection_id'));
    $this->forward404Unless($this->getUser()->isOwnerOf($collection));

    $collectible = CollectibleQuery::create()
      ->findOneById($request->getParameter('collectible_id'));
    $this->forward404Unless($this->getUser()->isOwnerOf($collectible));

    /** @var $image iceModelMultimedia */
    if ($image = $collectible->getPrimaryImage())
    {
      $collection->setPrimaryImage($image->getAbsolutePath('original'));
      $collection->save();

      return $this->success();
    }

    return $this->error('Error Title', 'Error Message');
  }

  protected function executeMultimediaDelete(sfWebRequest $request)
  {
    $multimedia = iceModelMultimediaQuery::create()
       ->findOneById($request->getParameter('multimedia_id'));
    $this->forward404Unless($this->getUser()->isOwnerOf($multimedia));

    if ($multimedia instanceof iceModelMultimedia)
    {
      if ($multimedia->getIsPrimary())
      {
        $model = $multimedia->getModelObject();
        if ($alternative = $model->getMultimedia(1, 'image', false, Propel::CONNECTION_WRITE))
        {
          $alternative->setIsPrimary(true);
          $alternative->save();
        }
      }

      $multimedia->delete();
    }

    return $this->success();
  }
}
