<?php

class marketplaceActions extends cqFrontendActions
{
  public function executeIndex()
  {
    $c = new Criteria();
    $c->setDistinct();
    $c->addJoin(CollectibleForSalePeer::COLLECTIBLE_ID, CollectiblePeer::ID);
    $c->add(CollectibleForSalePeer::IS_READY, true);
    $c->add(CollectibleForSalePeer::PRICE, 0, Criteria::GREATER_THAN);
    $c->setLimit(3);

    $this->spotlight = CollectibleForSalePeer::doSelect($c);

    $c = new Criteria();
    $c->setDistinct();
    $c->addJoin(CollectibleForSalePeer::COLLECTIBLE_ID, CollectiblePeer::ID);
    $c->add(CollectibleForSalePeer::IS_READY, true);
    $c->add(CollectibleForSalePeer::PRICE, 0, Criteria::GREATER_THAN);
    $c->setLimit(12);

    $this->collectibles_for_sale = CollectibleForSalePeer::doSelect($c);

    return sfView::SUCCESS;
  }

  public function executeBrowse(sfWebRequest $request)
  {
    $q = CollectibleForSaleQuery::create()
       ->joinCollectible()
       ->filterByIsReady(true)
       ->filterByPrice(0, Criteria::GREATER_THAN);

    $search = array();
    if ($request->getParameter('page'))
    {
      $search = $this->getUser()->getAttribute('search', array(), 'marketplace');
    }

    if ($search['price'] = $this->getRequestParameter('price', (array) @$search['price']))
    {
      $q->filterByPrice($search['price']);
    }
    if ($search['condition'] = $this->getRequestParameter('condition', @$search['condition']))
    {
      $q->filterByCondition($search['condition']);
    }

    /**
    if ($category = CollectionCategoryQuery::create()->findOneById($request->getParameter('id', @$search['category_id'])))
    {
      $c->add(CollectorCollectionPeer::COLLECTION_CATEGORY_ID, $category->getId());
      $c->addJoin(CollectiblePeer::ID, CollectionCollectiblePeer::COLLECTIBLE_ID, Criteria::RIGHT_JOIN);
      $c->addJoin(CollectionCollectiblePeer::COLLECTION_ID, CollectorCollectionPeer::ID);

      if ($category->getParentId() > 0)
      {
        $c->addOr(CollectorCollectionPeer::COLLECTION_CATEGORY_ID, $category->getParentId());
      }
    }


    if ($search['addtional_listing'] = $this->getRequestParameter('addtional_listing', @$search['addtional_listing']))
    {
      if ($search['addtional_listing'] == "Sold")
      {
        $c->add(CollectibleForSalePeer::IS_SOLD, true);
        $c->addDescendingOrderByColumn(CollectibleForSalePeer::UPDATED_AT);
      }
    }
    else
    {
      $c->add(CollectibleForSalePeer::IS_SOLD, false);
      $c->addDescendingOrderByColumn(CollectibleForSalePeer::CREATED_AT);
    }
    */

    $pager = new PropelModelPager($q, 10);
    $pager->setPage($this->getRequestParameter('page', 1));
    $pager->init();

    $this->pager = $pager;

    return sfView::SUCCESS;
  }

  public function executeCategories(sfWebRequest $request)
  {
    return sfView::SUCCESS;
  }
}
