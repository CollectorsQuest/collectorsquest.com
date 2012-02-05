<?php

/**
 * package actions.
 *
 * @package    collectornew
 * @subpackage package
 * @author     Prakash Panchal
 */
class marketplaceInfoActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->ssSortBy = ($request->getParameter('sort')) ? $request->getParameter('sort') : 'CREATED_AT';
    $this->ssSortType = ($request->getParameter('sory_type') == 'desc') ? 'asc' : 'desc';

    $oCriteria = new Criteria();
    $search = array();
    if ($search['search-term'] = $request->getParameter('search-term'))
    {
      $ssItemName = CollectiblePeer::NAME . " LIKE '%" . addslashes($search['search-term']) . "%' ";
      $ssItemDescription = CollectiblePeer::DESCRIPTION . " LIKE '%" . addslashes($search['search-term']) . "%' ";
      $oCriteria->addAnd(CollectiblePeer::NAME, $ssItemName, Criteria::CUSTOM);
      $oCriteria->addOr(CollectiblePeer::DESCRIPTION, $ssItemDescription, Criteria::CUSTOM);
    }

    // Changes By Prakash - 24-03-2011
    $search ['price-max'] = str_replace('MAX', '', $request->getParameter('price-max', ''));
    $search ['price-min'] = str_replace('MIN', '', $request->getParameter('price-min', ''));

    if ($search ['price-max'] != '' && !$search ['price-min'] != '')
    {
      $oCriteria->add(CollectibleForSalePeer::PRICE, (float) $search ['price-max'], Criteria::LESS_EQUAL);
    }
    if ($search ['price-min'] != '' && !$search ['price-max'] != '')
    {
      $oCriteria->add(CollectibleForSalePeer::PRICE, (float) $search ['price-min'], Criteria::GREATER_EQUAL);
    }
    if ($search ['price-min'] != '' && $search ['price-max'] != '')
    {
      $ssPriceCondition = CollectibleForSalePeer::PRICE . ' >= ' . (float) $search ['price-min'] . ' AND ' . CollectibleForSalePeer::PRICE . ' <= ' . (float) $search ['price-max'];
      $oCriteria->add(CollectibleForSalePeer::PRICE, $ssPriceCondition, Criteria::CUSTOM);
    }
    if ($search ['category_id'] = $request->getParameter('category_id'))
    {
      $oCriteria->addJoin(CollectiblePeer::COLLECTION_ID, CollectionPeer::ID);
      $oCriteria->add(CollectionPeer::COLLECTION_CATEGORY_ID, $search ['category_id']);
    }
    if ($search ['condition'] = $request->getParameter('condition'))
    {
      $oCriteria->addJoin(CollectibleForSalePeer::CONDITION, "'" . $search ['condition'] . "'");
    }
    if ($search ['addtional_listing'] = $request->getParameter('addtional_listing'))
    {
      if ($search ['addtional_listing'] == "Sold")
        $oCriteria->add(CollectibleForSalePeer::IS_SOLD, true);
    }
    else
      $oCriteria->add(CollectibleForSalePeer::IS_SOLD, false);

    if ($this->ssSortType == 'asc')
    {
      if ($this->ssSortBy == 'ITEM_ID')
        $oCriteria->addAscendingOrderByColumn(CollectiblePeer::NAME);
      else
        $oCriteria->addAscendingOrderByColumn(CollectibleForSalePeer::$this->ssSortBy);
    }
    else
    {
      if ($this->ssSortBy == 'ITEM_ID')
        $oCriteria->addDescendingOrderByColumn(CollectiblePeer::NAME);
      else
        $oCriteria->addDescendingOrderByColumn(CollectibleForSalePeer::$this->ssSortBy);
    }

    $oCriteria->addJoin(CollectibleForSalePeer::COLLECTIBLE_ID, CollectiblePeer::ID);

    $snPage = ($this->getRequestParameter('jpage')) ? $this->getRequestParameter('jpage', 1) : $this->getRequestParameter('page', 1);
    $oPager = new sfPropelPager('CollectibleForSale', 20);
    $oPager->setCriteria($oCriteria);
    $oPager->setPage($snPage);
    $oPager->init();

    $this->pager = $oPager;
  }

  public function executeItemOffers(sfWebRequest $request)
  {
    $this->item_for_sale = CollectibleForSalePeer::retrieveByPK($this->getRequestParameter('id'));
    $this->forward404Unless($this->item_for_sale);
    $this->item = $this->item_for_sale->getCollectible();
    $this->omItemOwner = $this->item->getCollection()->getCollector();

    $oCriteria = new Criteria();
    $oCriteria->addAscendingOrderByColumn(CollectibleOfferPeer::COLLECTOR_ID);
    $oCriteria->addDescendingOrderByColumn(CollectibleOfferPeer::UPDATED_AT);
    $oCriteria->add(CollectibleOfferPeer::COLLECTIBLE_ID, $this->item->getId());
    $this->offers = CollectibleOfferPeer::doSelect($oCriteria);
  }

}
