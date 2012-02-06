<?php

class _sidebarComponents extends sfComponents
{
  /**
   * @return string
   */
  public function executeWidgetFacebookLikeBox()
  {
    return sfView::SUCCESS;
  }

  /**
   * @return string
   */
  public function executeWidgetFacebookRecommendations()
  {
    return sfView::SUCCESS;
  }

  /**
   * @return string
   */
  public function executeWidgetRelatedCollections()
  {
    $this->collections = $this->getVar('collections') ? $this->getVar('collections') : array();

    // Set the limit of Artis to show
    $this->limit = $this->getVar('limit') ? $this->getVar('limit') : 5;

    /** @var $collectible Collectible */
    if ($collectible = $this->getVar('collectible'))
    {
      $this->collections = $collectible->getRelatedCollections($this->limit);
    }
    else
    {
      // Get some random collections
      $c = new Criteria();
      $c->add(CollectionPeer::NUM_ITEMS, 3, Criteria::GREATER_EQUAL);
      $this->collections = CollectionPeer::getRandomCollections($this->limit, $c);
    }

    if (count($this->collections) > 0)
    {
      return sfView::SUCCESS;
    }
    else if ($this->fallback && method_exists($this, 'execute'.$this->fallback))
    {
      echo get_component('_sidebar', $this->fallback, $this->getVarHolder()->getAll());
    }

    return sfView::NONE;
  }

  /**
   * @return string
   */
  public function executeWidgetAmazonProducts()
  {
    $this->products = $this->getVar('products') ? $this->getVar('products') : array();

    // Set the limit of Artis to show
    $this->limit = $this->getVar('limit') ? $this->getVar('limit') : 3;

    /** @var $collectible Collectible */
    if ($collectible = $this->getVar('collectible'))
    {
      $this->products = cqStatic::getAmazonProducts($this->limit, $collectible->getAmazonKeywords());
    }
    /** @var $collection Collection */
    else if ($collection = $this->getVar('collection'))
    {
      $this->products = cqStatic::getAmazonProducts($this->limit, $collection->getTags());
    }

    if (count($this->products) > 0)
    {
      return sfView::SUCCESS;
    }
    else if ($this->fallback && method_exists($this, 'execute'.$this->fallback))
    {
      echo get_component('_sidebar', $this->fallback, $this->getVarHolder()->getAll());
    }

    return sfView::NONE;
  }
}
