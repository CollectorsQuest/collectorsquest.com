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

    // Turning off the layout for this action
    $this->setLayout(false);

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
        $name = preg_replace('/\.(jpg|jpeg|png|gif|bmp)$/iu', '', $file['name']);
        $name = mb_substr(str_replace(array('_', '-'), ' ', ucfirst($name)), 0, 64, 'utf8');

        /**
         * We need to pass the name through the name validator
         * before we decide if we should be using it for Collectible name
         */
        try
        {
          $validator = new cqValidatorName();
          $collectible_name = $validator->clean($name);
          $is_name_automatic = true;
        }
        catch (sfValidatorError $e)
        {
          $collectible_name = null;
          $is_name_automatic = false;
        }

        try
        {
          $collectible = new Collectible();
          $collectible->setCollector($collector);
          $collectible->setName($collectible_name, $is_name_automatic);
          $collectible->setBatchHash($request->getParameter('batch', null));
          $collectible->setIsPublic(false);
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
      ->findOneById($request->getParameter('recipient_id'));
    $this->forward404Unless($this->getUser()->isOwnerOf($recipient));

    /** @var $donor Collectible */
    $donor = CollectibleQuery::create()
      ->findOneById($request->getParameter('donor_id'));
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

      // auto-set collection thumbnail if none set yet
      $collection = $recipient->getCollectorCollection();
      if (1 == $collection->countCollectibles() && !$collection->hasThumbnail())
      {
        $collection->setPrimaryImage($recipient->getPrimaryImage()
          ->getAbsolutePath('original'));
        $collection->save();
      }

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
   * @param  sfWebRequest  $request
   * @return string
   */
  protected function executeCollectibleDelete(sfWebRequest $request)
  {
    /** @var $collectible Collectible */
    $collectible = CollectibleQuery::create()
      ->findOneById($request->getParameter('collectible_id'));
    $this->forward404Unless($collectible instanceof Collectible);

    $collection = CollectorCollectionQuery::create()
      ->findOneById($request->getParameter('collection_id'));

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
      }
      else
      {
        $multimedia->delete();
      }

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

    return $this->success();
  }

  protected function executeCollectorAvatarFromDefault(sfWebRequest $request)
  {
    $avatars = CollectorPeer::$default_avatar_ids;

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
   * page: createStep1
   */
  protected function executeCollectionCreateStep1(sfWebRequest $request, $template)
  {
    $form = new CollectionCreateForm();
    $form->setDefault('collectible_id', $request->getParameter('collectible_id'));

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

        if (isset($values['collectible_id']))
        {
          $q = CollectibleQuery::create()
            ->filterByCollector($this->getUser()->getCollector())
            ->filterById($values['collectible_id']);

          if (($collectible = $q->findOne()) && $this->getUser()->isOwnerOf($collectible))
          {
            // Let's create the CollectionCollectible
            $q = CollectionCollectibleQuery::create()
              ->filterByCollection($collection)
              ->filterByCollectible($collectible);

            $collection_collectible = $q->findOneOrCreate();
            $collection_collectible->save();

            /**
             * If the Collectible has a thumnail (it should!),
             * let's add it as the Collection thumbnail also
             *
             * @var $thumbnail iceModelMultimedia
             */
            if ($thumbnail = $collectible->getPrimaryImage())
            {
              $collection->setThumbnail($thumbnail->getAbsolutePath('original'));
              $collection->save();
            }

          }
        }

        $this->getUser()->getCollector()->getProfile()->updateProfileProgress();

        return $this->redirect('ajax_mycq', array(
            'section' => 'collection',
            'page' => 'createStep2',
            'collection_id' => $collection->getId(),
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
   * page: createStep2
   * params: collection_id
   */
  public function executeCollectionCreateStep2(sfWebRequest $request, $template)
  {
    $collection = CollectorCollectionPeer::retrieveByPK(
      $request->getParameter('collection_id')
    );
    $this->forward404Unless($collection &&
      $this->getUser()->getCollector()->isOwnerOf($collection));

    $form = new CollectorCollectionEditForm($collection);
    if ($collection->hasThumbnail())
    {
      $form->useFields(array(
          'description',
      ));
    }
    else
    {
      $form->useFields(array(
          'thumbnail',
          'description',
      ));
    }

    if (sfRequest::POST == $request->getMethod())
    {
      $taintedValues = $request->getParameter($form->getName());
      $form->bind($taintedValues, $request->getFiles($form->getName()));

      if ($form->isValid())
      {
        $values = $form->getValues();
        $collection->setDescription($values['description'], 'html');

        if (isset($values['thumbnail']))
        {
          $collection->setThumbnail($values['thumbnail']);
        }

        $collection->save();

        // Tell the Dropbox to stay closed
        $this->getUser()->setFlash('cq_mycq_dropbox_open', false, true, 'cookies');

        return $this->renderPartial('global/loading', array(
            'url' => $this->generateUrl('mycq_collection_by_section', array(
                'id' => $collection->getId(),
                'section' => 'details',
            )),
        ));
      }
    }

    $this->form = $form;
    $this->collection = $collection;

    return $template;
  }

  /**
   * section: collection
   * page: changeCategoty
   * params: collection_id
   */
  public function executeCollectionChangeCategory(sfWebRequest $request, $template)
  {
    $collection = CollectorCollectionPeer::retrieveByPK(
      $request->getParameter('collection_id')
    );
    $this->forward404Unless($this->getUser()->isOwnerOf($collection));

    $form = new CollectionCreateForm($collection);
    $form->useFields(array('content_category_id'));

    if (sfRequest::POST == $request->getMethod())
    {
      $taintedValues = $request->getParameter($form->getName());
      $form->bind($taintedValues, $request->getFiles($form->getName()));

      if ($form->isValid())
      {
        $form->save();

        return $this->renderPartial('global/loading', array(
            'url' => $this->generateUrl('mycq_collection_by_section', array(
                'id' => $collection->getId(),
                'section' => 'details',
            )),
        ));
      }
    }

    $this->form = $form;
    $this->collection = $collection;

    $root = ContentCategoryQuery::create()->findRoot();
    $this->categories = ContentCategoryQuery::create()
        ->descendantsOf($root)
        ->findTree();

    return $template;
  }

  /**
   * section: collectible
   * page: create
   * params: collection_id
   */
  public function executeCollectibleCreate(sfWebRequest $request, $template)
  {
    /** @var $collector Collector */
    $collector = $this->getUser()->getCollector(true);

    $form = new CollectibleCreateForm();

    $q = CollectorCollectionQuery::create()
      ->filterById($request->getParameter('collection_id'));

    if ($collection = $q->findOne())
    {
      $form->setDefault('collection_id', $collection->getId());
      $form->setDefault('tags', $collection->getTags());
    }

    if ($collectible_id = $request->getParameter('collectible_id'))
    {
      $q = CollectibleQuery::create()
        ->filterByCollector($collector)
        ->filterById($collectible_id);

      /**
       * @var $image iceModelMultimedia
       * @var $collectible Collectible
       */
      if (($collectible = $q->findOne()) && $image = $collectible->getPrimaryImage())
      {
        $form->setDefault('thumbnail', $image->getId());
      }
    }

    if ($request->isMethod('post'))
    {
      $form->bind($request->getParameter('collectible'));

      if ($form->isValid())
      {
        $values = $form->getValues();

        $collection = CollectorCollectionQuery::create()
          ->findOneById($values['collection_id']);

        if (!$collector->isOwnerOf($collection))
        {
          return sfView::NONE;
        }

        $values = $form->getValues();
        $values['collector_id'] = $collector->getId();

        /** @var $collectible Collectible */
        $collectible = $form->updateObject($values);
        $collectible->setDescription($values['description'], 'html');
        $collectible->setTags($values['tags']);
        $collectible->save();

        $collectible->addCollection($collection);
        $collectible->save();

        if (isset($values['thumbnail']))
        {
          $image = iceModelMultimediaQuery::create()
            ->findOneById((integer) $values['thumbnail']);

          if ($collector->isOwnerOf($image))
          {
            $collectible->setThumbnail($image->getAbsolutePath('original'));
            $collectible->save();
          }
        }

        $this->collectible = $collectible;
      }
    }

    $this->form = $form;

    return $template;
  }

  public function executeCollectibleForSaleCreate(sfWebRequest $request, $template)
  {
    /** @var $collector Collector */
    $collector = $this->getUser()->getCollector(true);

    $form = new CollectibleForSaleCreateForm();

    if ($collectible_id = $request->getParameter('collectible_id'))
    {
      $q = CollectibleQuery::create()
        ->filterByCollector($collector)
        ->filterById($collectible_id);

      if ($collectible = $q->findOne())
      {
        $form->setDefault('collectible_id', $collectible->getId());

        $default = (array) $form->getDefault('collectible');
        $default['name'] = $collectible->getName();

        /** @var $image iceModelMultimedia */
        if ($image = $collectible->getPrimaryImage())
        {
          $default['thumbnail'] = $image->getId();
        }

        $form->setDefault('collectible', $default);
      }
    }

    if ($request->isMethod('post'))
    {
      $tainted = $request->getParameter('collectible_for_sale');

      /**
       * The logic below is to support creating of new CollectorCollections
       * if the select option's value is not numeric but a string, this
       * shows us that we need to create the collection rather than use
       * an already existing CollectorCollection
       */
      if (!empty($tainted['collectible']['collection_collectible_list']))
      {
        $collection_collectible_list = &$tainted['collectible']['collection_collectible_list'];
        foreach ($collection_collectible_list as $i => $id)
        {
          if (!is_numeric($id) && !empty($id))
          {
            $collection = new CollectorCollection();
            $collection->setCollector($collector);
            $collection->setName($id);

            try
            {
              $collection->save();
              $collection_collectible_list[$i] = $collection->getId();
            }
            catch (PropelException $e)
            {
              ;
            }
          }
        }
      }

      $form->bind($tainted);

      if ($form->isValid())
      {
        $values = $form->getValues();
        $values['collector_id'] = $collector->getId();

        /** @var $collectible_for_sale CollectibleForSale */
        $collectible_for_sale = $form->updateObject($values);

        /** @var $collectible Collectible */
        $collectible = $collectible_for_sale->getCollectible();

        $collectible->setTags($values['collectible']['tags']);
        $collectible->save();

        if ($values['collectible_id']['thumbnail'])
        {
          $image = iceModelMultimediaQuery::create()
            ->findOneById((integer) $values['collectible']['thumbnail']);

          if ($collector->isOwnerOf($image))
          {
            /** @var $donor Collectible */
            $donor = $image->getModelObject();

            $image->setIsPrimary(true);
            $image->setModelId($collectible->getId());
            $image->setSource($donor->getId());
            $image->save();

            // Archive the $donor, not needed anymore
            $donor->delete();
          }
        }

        $this->collectible = $collectible;
      }
      else
      {
        ;
      }
    }

    $this->form = $form;

    return $template;
  }

  public function executeAccountDelete(sfWebRequest $request, $template)
  {
    $form = new ConfirmDestructiveActionForm();
    $form->getWidget('input')
         ->setLabel('TYPE "DELETE" TO CONFIRM THE DELETION OF YOUR ACCOUNT');

    if ($this->getRequest()->isMethod('post'))
    {
      $form->bind($request->getParameter('confirm'), array());
      if ($form->isValid())
      {
        if ($this->getUser()->delete())
        {
          $this->getUser()->getFlash(
            'success', 'Thank you for being part of Collectors Quest!
                        Your account with us is now suspended.',
            true
          );

          return $this->renderPartial('global/loading', array(
            'url' => $this->generateUrl('homepage'),
          ));
        }
        else
        {
          $message = sprintf(
            'We are sorry but your account cannot be currently deleted!
           Please contact the customer support (<a href="mailto:%s">%s</a>) for more information.',
            'custsrv@collectorsquest.com', 'custsrv@collectorsquest.com'
          );
          $form->getErrorSchema()->addError(
            new sfValidatorError(new sfValidatorPass(), $message), 'input'
          );
        }
      }
    }

    $this->form = $form;

    return $template;
  }

}
