<?php

class ajaxAction extends cqAjaxAction
{
  protected function getObject(sfRequest $request)
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
      $firstUpload = 0 == $collector->countCollectibles();

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
          else
          {
            // We do not want to have a Collectibles without Mutlimedia
            $collectible->delete();
          }
        }
        catch (Exception $e)
        {
          if ($collectible && !$collectible->isNew())
          {
            $collectible->delete();
          }

          return $this->error($e->getCode(), $e->getMessage(), false);
        }
      }

      if ($firstUpload)
      {
        $collector->getProfile()->updateProfileProgress();
      }

      // change the dropbox open status depending on whether we have stuff
      // left in it
      $this->getUser()->setMycqDropboxOpenState(true);

      // This is for xdcomm support in IE browsers
      if ($redirect = $request->getParameter('redirect'))
      {
        $redirect = sprintf($redirect, urlencode(json_encode($output)));
        $this->redirect($redirect);
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
   * @param  sfWebRequest  $request
   *
   * @throws PropelException
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
      else if (!$is_primary && !$recipient->getPrimaryImage())
      {
        $is_primary = true;
      }

      try
      {
        $image->setIsPrimary($is_primary);
        $image->setModelId($recipient->getId());
        $image->setSource($donor->getId());
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

      // Archive the $donor, not needed anymore
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
   * @param  sfWebRequest  $request
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
      $object = $multimedia->getModelObject();

      if ($multimedia->getIsPrimary())
      {
        $model = $multimedia->getModelObject();
        if ($alternative = $model->getMultimedia(1, 'image', false, Propel::CONNECTION_WRITE))
        {
          $alternative->setIsPrimary(true);
          $alternative->save();
        }
      }

      /** @var $archive CollectibleArchive */
      if (
        ($source = $multimedia->getSource()) &&
        ($archive = CollectibleArchiveQuery::create()->findOneById((integer) $source))
      )
      {
        $collectible = new Collectible();
        $collectible->populateFromArchive($archive);
        $collectible->save();

        $multimedia->setModel($collectible);
        $multimedia->setSource(null);
        $multimedia->save();

        /**
         * Update the Eblob cache
         */
        if ($object)
        {
          $m = iceModelMultimediaPeer::retrieveByModel($object);

          $object->setEblobElement('multimedia', $m->toXML(true));
          $object->save();
        }
      }
      else
      {
        $multimedia->delete();
      }
    }

    return $this->success();
  }

  protected function executeCollectorAvatarFromDefault(sfWebRequest $request)
  {
    $avatars = CollectorPeer::$avatars;

    $avatar_id = $request->getParameter('avatar_id');
    $this->forward404Unless($avatar_id && false !== array_search($avatar_id, $avatars));

    /** @var $collector Collector */
    $collector = $this->getUser()->getCollector();

    $image = sprintf(
      '%s/images/frontend/multimedia/Collector/default/235x315/%s.jpg',
      sfConfig::get('sf_web_dir'), $avatar_id
    );

    /** @var $multimedia iceModelMultimedia */
    if ($multimedia = $collector->setPhoto($image))
    {
      /**
       * We want to copy here optimized 100x100 thumb,
       * rather than the automatically generated one
       */
      $small = $multimedia->getAbsolutePath('100x100');
      copy(str_replace('235x315', '100x100', $image), $small);

      $collector->getProfile()->setIsImageAuto(true);
      $collector->getProfile()->save();

      return $this->success();
    }

    return $this->error('Error', 'Error');
  }

  protected function executeCollectorAvatarDelete()
  {
    /** @var $collector Collector */
    $collector = $this->getUser()->getCollector();

    if ($image = $collector->getPhoto())
    {
      $image->delete();
    }

    return $this->success();
  }

  /**
   * section: collection
   * page: create
   */
  protected function executeCollectionCreate(sfWebRequest $request, $template)
  {
    $form = new CollectionCreateForm();

    if ($collectible_id = $request->getParameter('collectible_id'))
    {
      $q = CollectibleQuery::create()
          ->filterByCollector($this->getUser()->getCollector())
          ->filterById($collectible_id);

      /** @var $image iceModelMultimedia */
      if (($collectible = $q->findOne()) && $image = $collectible->getPrimaryImage())
      {
        $form->setDefault('thumbnail', $image->getId());
      }
    }

    if (sfRequest::POST == $request->getMethod())
    {
      $form->bind($request->getParameter('collection'));
      if ($form->isValid())
      {
        $values = $form->getValues();
        $values['collector_id'] = $this->getUser()->getCollector()->getId();

        /** @var $collection CollectorCollection */
        $collection = $form->updateObject($values);
        $collection->setTags($values['tags']);
        $collection->save();

        if ($values['thumbnail'])
        {
          $image = iceModelMultimediaQuery::create()
              ->findOneById((integer) $values['thumbnail']);

          if ($this->getUser()->getCollector()->isOwnerOf($image))
          {
            $collection->setThumbnail($image->getAbsolutePath('original'));
            $collection->save();
          }
        }

        $this->getUser()->getCollector()->getProfile()->updateProfileProgress();

        return $this->redirect('ajax_mycq', array(
            'section' => 'collection',
            'page' => 'setDetailsAndThumbnail',
            'collection-id' => $collection->getId(),
        ));
      }
    }

    $root = ContentCategoryQuery::create()->findRoot();
    $this->categories = ContentCategoryQuery::create()
        ->descendantsOf($root)
        ->findTree();

    $this->form = $form;

    return $template;
  }

  /**
   * section: collection
   * page: setDetailsAndThumbanil
   *
   */
  public function executeCollectionSetDetailsAndThumbnail(sfWebRequest $request, $template)
  {
    $collection = CollectorCollectionPeer::retrieveByPK(
      $request->getParameter('collection-id')
    );
    $this->forward404Unless($collection);

    $form = new CollectorCollectionEditForm($collection);
    $form->useFields(array(
        'thumbnail',
        'description',
    ));

    if (sfRequest::POST == $request->getMethod())
    {
      $taintedValues = $request->getParameter($form->getName());
      $form->bind($taintedValues, $request->getFiles($form->getName()));

      if ($form->isValid())
      {
        $values = $form->getValues();

        $collection->setName($values['name']);
        $collection->setDescription($values['description'], 'html');

        if ($values['thumbnail'] instanceof sfValidatedFile)
        {
          $collection->setThumbnail($values['thumbnail']);
        }

        $collection->save();

        return $this->renderPartial('global/loading', array(
            'url' => $this->generateUrl('mycq_collection_by_section', array(
                'id' => $collection->getId(),
                'section' => 'collectibles',
            )),
        ));
      }
    }

    $this->form = $form;
    $this->collection = $collection;

    return $template;
  }


}
