<?php

class marketplaceComponents extends sfComponents
{
  /**
   * @param  sfWebRequest $request
   * @return string
   */
  public function executeListing($request)
  {
    $c = new Criteria();
    $c->addJoin(CollectibleForSalePeer::COLLECTIBLE_ID, CollectiblePeer::ID);

    $search = array();
    if ($request->getParameter('page'))
    {
      $search = $this->getUser()->getAttribute('search', array(), 'marketplace');
    }

    if ($search['search-term'] = $request->getParameter('search-term', @$search['search-term']))
    {
      $searchTerm = addslashes($search['search-term']);
      $pks_NAME = CollectiblePeer::NAME . " LIKE '%" . $searchTerm . "%' ";
      $pks_DESCRIPTION = CollectiblePeer::DESCRIPTION . " LIKE '%" . $searchTerm . "%' ";
      $pks_CATEGORY = sprintf('%s LIKE "%%%s%%"', CollectionCategoryPeer::NAME, $searchTerm);

      $crit = $c->getNewCriterion(CollectiblePeer::DESCRIPTION, $pks_DESCRIPTION, Criteria::CUSTOM);
      $crit->addOr($c->getNewCriterion(CollectiblePeer::NAME, $pks_NAME, Criteria::CUSTOM));
      $crit->addOr($c->getNewCriterion(CollectionCategoryPeer::NAME, $pks_CATEGORY, Criteria::CUSTOM));

      // Search in category name
      // $c->addJoin(CollectionPeer::ID, CollectiblePeer::COLLECTION_ID, Criteria::LEFT_JOIN);
      // $c->addJoin(CollectionPeer::COLLECTION_CATEGORY_ID, CollectionCategoryPeer::ID, Criteria::LEFT_JOIN);
      $c->addAnd($crit);
    }

    $search['price-max'] = str_replace('Max', '', $this->getRequestParameter('price-max', @$search['price-max']));
    $search['price-min'] = str_replace('Min', '', $this->getRequestParameter('price-min', @$search['price-min']));

    if ($search['price-max'] && !$search['price-min'])
    {
      $c->add(CollectibleForSalePeer::PRICE, (float) $search['price-max'], Criteria::LESS_EQUAL);
    }
    if ($search['price-min'] && !$search['price-max'])
    {
      $c->add(CollectibleForSalePeer::PRICE, (float) $search['price-min'], Criteria::GREATER_EQUAL);
    }
    if ($search['price-min'] && $search['price-max'])
    {
      $ssPriceCondition = CollectibleForSalePeer::PRICE . ' >= ' . (float) $search['price-min'] . ' AND ' . CollectibleForSalePeer::PRICE . ' <= ' . (float) $search['price-max'];
      $c->add(CollectibleForSalePeer::PRICE, $ssPriceCondition, Criteria::CUSTOM);
    }

    if ($category = CollectionCategoryQuery::create()->findOneById($request->getParameter('id', @$search['category_id'])))
    {
      $c->addJoin(CollectiblePeer::COLLECTION_ID, CollectionPeer::ID);
      $c->add(CollectionPeer::COLLECTION_CATEGORY_ID, $category->getId());
      if ($category->getParentId() > 0)
      {
        $c->addOr(CollectionPeer::COLLECTION_CATEGORY_ID, $category->getParentId());
      }
    }
    if ($search['condition'] = $this->getRequestParameter('condition', @$search['condition']))
    {
      $c->add(CollectibleForSalePeer::CONDITION, $search['condition']);
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

    $pager = new sfPropelPager('CollectibleForSale', 10);
    $pager->setCriteria($c);

    $snPage = ($this->getRequestParameter('jpage')) ? $this->getRequestParameter('jpage', 1) : $this->getRequestParameter('page', 1);

    $pager->setPage($snPage);
    $pager->init();

    $this->pager = $pager;

    $this->getUser()->setAttribute('search', $search, 'marketplace');

    return sfView::SUCCESS;
  }

  public function executeSidebar()
  {
    return sfView::SUCCESS;
  }
}
