<?php

class collectionAction extends cqAjaxAction
{
  /**
   * @param  sfWebREquest  $request
   * @return sfView
   */
  public function execute($request)
  {
    /** @var $collection collection */

    if ($pk = $request->getParameter('id'))
    {
      $collection = CollectionPeer::retrieveByPK($pk);
    }
    else if ($pk = $request->getParameter('collectible_id'))
    {
      $collectible = CollectiblePeer::retrieveByPK($pk);
      if ($collectible)
      {
        $collection = $collectible->getCollectionId();
      }
    }
    else
    {
      $collection = null;
    }

    $section = $request->getParameter('section');
    $page = $request->getParameter('page');

    $template = str_replace(' ', '', ucwords(str_replace('-', ' ', $section) .' '. $page));
    $method = 'execute'.$template;

    if ($section == 'partial')
    {
      return $this->renderPartial('collection/'. $page, array('collection' => $collection));
    }
    else if ($section == 'component')
    {
      return $this->renderComponent('collection', $page, array('collection' => $collection));
    }

    $this->collection  = $collection;
    $this->collectible = isset($collectible) ? $collectible : null;

    return $this->$method($request, $template);
  }

  /**
   * @param sfWebRequest $request
   *
   * @return sfView::NONE
   */
  protected function executeUploadCollectibles(sfWebRequest $request)
  {
    $collector = $this->getUser()->getCollector();

    if ($this->collection instanceof Collection)
    {
      $this->forward404Unless($collector && $collector->isOwnerOf($this->collection));
    }

    if ($request->isMethod('post'))
    {
      $file = $request->getFiles('Filedata');
      $name = preg_replace('/\.(jpg|jpeg|png|gif)$/iu', '', $file['name']);
      $name = mb_substr(str_replace('_', ' ', ucfirst($name)), 0, 64, 'utf8');

      try
      {
        $collectible = new Collectible();
        $collectible->setCollector($collector);
        $collectible->setCollection($this->collection);
        $collectible->setName($name, true);
        $collectible->setBatchHash($request->getParameter('batch', null));
        $collectible->save();

        // Add the image
        if ($multimedia = $collectible->addMultimedia($file['tmp_name'], true, true))
        {
          $multimedia->setName($name);
          $multimedia->save();
        }
      }
      catch (PropelException $e)
      {
        ;
      }
    }
    else
    {
      return $this->forward404();
    }

    // We do not want the web debug bar on these requests
    sfConfig::set('sf_web_debug', false);

    $this->renderText('An old version of the Flash plugin was detected. <strong><a href="http://www.macromedia.com/go/getflash/">Please upgrade your Flash plugin.</a></strong>');
    return sfView::NONE;
  }

  /**
   * @param sfWebRequest $request
   *
   * @return sfView::NONE
   */
  protected function executeReorderCollectibles(sfWebRequest $request)
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

      $q = new CollectibleQuery();
      $q->filterByPrimaryKeys($pks);

      /** @var $collectibles Collectible[] */
      $collectibles = $q->find();

      foreach ($collectibles as $collectible)
      {
        foreach ($order[$key] as $position => $pk)
        {
          if ($collectible->getId() == $pk && $collectible->getPosition() != $position)
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

  public function executeCollectibleRotate()
  {
    $this->forward404Unless($this->collectible);

    // Do the rotate
    $this->collectible->rotateMultimedia(true, true);

    // We do not want the web debug bar on these requests
    sfConfig::set('sf_web_debug', false);

    return sfView::NONE;
  }

  public function executeCollectibleDelete()
  {
    $this->forward404Unless($this->collectible);

    // Do the delete
    $this->collectible->delete();

    // We do not want the web debug bar on these requests
    sfConfig::set('sf_web_debug', false);

    return sfView::NONE;
  }
}
