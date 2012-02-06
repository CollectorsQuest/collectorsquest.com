<?php

class collectionComponents extends cqComponents
{
  public function executeSidebar()
  {
    if ($id = $this->getRequestParameter('id'))
    {
      $this->collection = CollectionPeer::retrieveByPk($id);
    }
    else if ($id = $this->getRequestParameter('collector_id'))
    {
      if ($collector = CollectorPeer::retrieveByPK($id))
      {
        $this->collection = $collector->getCollectionDropbox();
      }
    }

    if ($this->collection)
    {
      $this->related_collections = $this->collection->getRelatedCollections(5);
    }

    if ($this->getUser()->isOwnerOf($this->collection))
    {
      $this->buttons = array();

      if (!($this->collection instanceof CollectionDropbox))
      {
        $this->buttons[] = array(
          'text' => 'Edit Collection',
          'icon' => 'pencil',
          'route' => '@manage_collection_by_slug?id='. $this->collection->getId(). '&slug='. $this->collection->getSlug()
        );
      }

      $this->buttons[] = array(
        'text' => 'Edit Collectibles',
        'icon' => 'pencil',
        'route' => '@manage_collectibles_by_slug?id='. $this->collection->getId() .'&slug='. $this->collection->getSlug()
      );

      $this->buttons[] = array(
        'text' => 'Add Collectibles',
        'icon' => 'plus',
        'route' => 'fancybox_collection_add_collectibles('. $this->collection->getId() .')'
      );

      $this->buttons[] = array(
        'text' => 'Re-Order Collectibles',
        'icon' => 'refresh',
        'route' => 'ajax_load("#contents", "'. url_for('@ajax_collection?section=component&page=collectiblesReorder') .'?id='. $this->collection->getId() .'")'
      );


      if ($this->collection instanceof CollectionDropbox)
      {
        $this->buttons[] = array(
          'text' => 'Empty Dropbox',
          'icon' => 'trash',
          'route' => '@manage_dropbox?cmd=empty&encrypt=1'
        );
      }
    }
    else
    {
      $this->buttons = array(
        0 => array(
          'text' => 'Message Collector',
          'icon' => 'mail-closed',
          'route' => '@message_compose?'.
                     'to='. $this->collection->getCollectorId() .
                     '&subject='. urlencode('Your collection "'. $this->collection->getName().'"')
        ),
        /*
        1 => array(
          'text' => 'Add to Watch List',
          'icon' => 'plus',
          'route' => '@homepage'
        )
        */
      );
    }

    return sfView::SUCCESS;
  }

  public function executeSidebarCollectible()
  {
    $this->collectible = CollectiblePeer::retrieveByPk($this->getRequestParameter('id'));
    $collection = $this->collectible->getCollection();

    $this->buttons = array(
      0 => array(
        'text' => 'Back to Collection',
        'icon' => 'arrowreturnthick-1-w',
        'route' => route_for_collection($collection)
      )
    );

    if ($this->getUser()->isOwnerOf($this->collectible))
    {
      $this->buttons[] = array(
        'text' => 'Edit Collectible',
        'icon' => 'pencil',
        'route' => '@manage_collectible_by_slug?id='. $this->collectible->getId() .'&slug='. $this->collectible->getSlug()
      );
      $this->buttons[] = array(
        'text' => 'Delete Collectible',
        'icon' => 'trash',
        'route' => '@manage_collectible_by_slug?id='. $this->collectible->getId() .'&slug='. $this->collectible->getSlug() .'&cmd=delete&encrypt=1'
      );
    }
    else
    {
      $this->buttons[] = array(
        'text' => 'Message Collector',
        'icon' => 'mail-closed',
        'route' => '@message_compose?'.
                   'to='. $this->collectible->getCollectorId() .
                   '&subject='. urlencode('Your collectible "'. $this->collectible->getName().'"')
      );
    }

    return sfView::SUCCESS;
  }

  public function executeCollectiblesReorder()
  {
    if ($id = $this->getRequestParameter('id'))
    {
      $this->collection = CollectionPeer::retrieveByPk($id);
    }
    else if ($id = $this->getRequestParameter('collector_id'))
    {
      if ($collector = CollectorPeer::retrieveByPK($id))
      {
        $this->collection = $collector->getCollectionDropbox();
      }
    }
    else if ('0' === $id = $this->getRequestParameter('id'))
    {
      if ($collector = $this->getCollector())
      {
        $this->collection = $collector->getCollectionDropbox();
      }
    }

    if ($this->getUser()->isOwnerOf($this->collection))
    {
      $c = new Criteria();
      $c->addAscendingOrderByColumn(CollectiblePeer::POSITION);
      $c->addDescendingOrderByColumn(CollectiblePeer::CREATED_AT);

      $this->collectibles = $this->collection->getCollectibles($c);
    }

    return sfView::SUCCESS;
  }

}
