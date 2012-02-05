<?php

class manageComponents extends cqComponents
{
  public function executeSidebarProfile()
  {
    $this->buttons = array(
      0 => array(
        'text' => 'View Your Profile',
        'icon' => 'note',
        'route' => '@collector_by_id?id='.$this->getUser()->getId().'&slug='.$this->getUser()->getSlug()
      )
    );

    return sfView::SUCCESS;
  }

  public function executeSidebarFriends()
  {
    $this->buttons = array(
      0 => array(
        'text' => 'View Your Profile',
        'icon' => 'note',
        'route' => '@collector_by_id?id='.$this->getUser()->getId().'&slug='.$this->getUser()->getSlug()
      )
    );

    return sfView::SUCCESS;
  }

  public function executeSidebarCollections()
  {
    $this->buttons = array(
      0 => array(
        'text' => 'Create a Collection',
        'icon' => 'plus',
        'route' => '@collection_create'
      ),
      1 => array(
        'text' => 'Upload Collectibles',
        'icon' => 'arrowthick-1-n',
        'route' => 'fancybox_collection_add_collectibles(0)'
      )
    );

    return sfView::SUCCESS;
  }

  public function executeSidebarCollection()
  {
    $collection = CollectionPeer::retrieveByPK($this->getRequestParameter('id', $this->getRequestParameter('collection[id]')));

    $this->buttons = array(
      0 => array(
        'text' => 'Back to Your Collections',
        'icon' => 'arrowreturnthick-1-w',
        'route' => '@manage_collections'
      )
    );

    if ($collection instanceof Collection)
    {
      $this->buttons[] = array(
        'text' => 'Add Collectibles',
        'icon' => 'plus',
        'route' => 'fancybox_collection_add_collectibles('. $collection->getId() .')'
      );
      $this->buttons[] = array(
        'text' => 'View Collectibles',
        'icon' => 'image',
        'route' => route_for_collection($collection)
      );
    }

    return sfView::SUCCESS;
  }

  public function executeSidebarCollectible()
  {
    $collectible = CollectiblePeer::retrieveByPK($this->getRequestParameter('id'));

    if ($collectible instanceof Collectible)
    {
      $collection = $collectible->getCollection();

      $this->buttons = array(
        0 => array(
          'text' => 'Back to Collection',
          'icon' => 'arrowreturnthick-1-w',
          'route' => route_for_collection($collection)
        ),
        1 => array(
          'text' => 'View Collectible',
          'icon' => 'image',
          'route' => route_for_collectible($collectible)
        ),
        2 => array(
          'text' => 'Delete Collectible',
          'icon' => 'trash',
          'route' => '@manage_collectible_by_slug?id='. $collectible->getId() .'&slug='. $collectible->getSlug() .'&cmd=delete&encrypt=1'
        )
      );
    }

    return sfView::SUCCESS;
  }

  public function executeSidebarCollectibles()
  {
    // Get the currently logged in Collector
    $collector = $this->getCollector();

    // Either get the collection by ID, or get the Dropbox
    if (!$collection = CollectionPeer::retrieveByPK($this->getRequestParameter('id')))
    {
      $collection = new CollectionDropbox($collector->getId());
    }

    if ($collection instanceof Collection)
    {
      $this->buttons = array(
        0 => array(
          'text' => 'Back to Collection',
          'icon' => 'arrowreturnthick-1-w',
          'route' => route_for_collection($collection)
        ),
        array(
          'text' => 'Add Collectibles',
          'icon' => 'plus',
          'route' => 'fancybox_collection_add_collectibles('. $collection->getId() .')'
        ),
        array(
          'text' => 'View Collectibles',
          'icon' => 'image',
          'route' => route_for_collection($collection)
        )
      );
    }

    return sfView::SUCCESS;
  }

  public function executeSidebarMarketplaceEmpty()
  {
    if ($this->getUser()->hasCredential('seller'))
    {
      /** @var $collector Collector */
      $collector = $this->getUser()->getCollector();

      $this->buttons = array(
        0 => array(
          'text' => 'Sell Your Collectibles',
          'icon' => 'plus',
          'route' => $collector->countCollections() == 0 ? '@collection_create' : '@manage_collections'
        ),
        1 => array(
          'text' => 'Buy Collectibles',
          'icon' => 'plus',
          'route' => '@marketplace'
        )
      );
    }
    else
    {
      $this->buttons = array(
        0 => array(
          'text' => 'Sell Your Collectibles',
          'icon' => 'plus',
          'route' => '@seller_become'
        ),
        1 => array(
          'text' => 'Buy Collectibles',
          'icon' => 'plus',
          'route' => '@marketplace'
        )
      );
    }

    return sfView::SUCCESS;
  }

}
