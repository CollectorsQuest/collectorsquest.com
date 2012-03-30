<?php

class _sidebarComponents extends cqFrontendComponents
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
  public function executeWidgetCollectionCategories()
  {
    // Set the limit of Collections to show
    $this->limit = $this->getVar('limit') ? (int) $this->getVar('limit') : 30;

    // Set the number of columns to show
    $this->columns = $this->getVar('columns') ? (int) $this->getVar('columns') : 2;

    $q = CollectionCategoryQuery::create()
      ->filterById(0, Criteria::NOT_EQUAL)
      ->filterByParentId(0, Criteria::EQUAL)
      ->orderByName(Criteria::ASC)
      ->limit($this->limit);
    $categories = $q->find();

    $this->categories = IceFunctions::array_vertical_sort($categories, $this->columns);

    return sfView::SUCCESS;
  }

  /**
   * @return string
   */
  public function executeWidgetRelatedCollections()
  {
    $this->collections = $this->getVar('collections') ? $this->getVar('collections') : array();

    // Set the limit of Collections to show
    $this->limit = $this->getVar('limit') ? $this->getVar('limit') : 5;

    /** @var $collectible Collectible */
    if ($collectible = $this->getVar('collectible'))
    {
      $this->collections = $collectible->getRelatedCollections($this->limit);
    }
    else if (empty($this->collections))
    {
      // Get some random collections
      $c = new Criteria();
      $c->add(CollectorCollectionPeer::NUM_ITEMS, 3, Criteria::GREATER_EQUAL);
      $this->collections = CollectorCollectionPeer::getRandomCollections($this->limit, $c);
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

  public function executeWidgetTagsCloud()
  {
    $this->tags = $this->getVar('tags') ? $this->getVar('tags') : array();

    // Set the limit of Tags to show
    $this->limit = $this->getVar('limit') ? $this->getVar('limit') : 0;

    /** @var $collectible Collectible */
    if ($collectible = $this->getVar('collectible'))
    {
      $this->tags = $collectible->getTags();
    }

    if (count($this->tags) > 0)
    {
      return sfView::SUCCESS;
    }
    else if ($this->fallback && method_exists($this, 'execute'.$this->fallback))
    {
      echo get_component('_sidebar', $this->fallback, $this->getVarHolder()->getAll());
    }

    return sfView::NONE;
  }

  public function executeWidgetMemberVideos()
  {
    return sfView::SUCCESS;
  }

}
