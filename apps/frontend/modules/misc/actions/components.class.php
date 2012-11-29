<?php

class miscComponents extends cqFrontendComponents
{
  public function executeSidebarWordPressFeaturedItems()
  {
    if (!$wp_post = wpPostQuery::create()->findOneById($this->getRequestParameter('id')))
    {
      return sfView::NONE;
    }

    /** @var $values array */
    $values = $wp_post->getPostMetaValue('_featured_items');

    // Initialize the arrays
    $collectibles_for_sale_ids = $wp_post_ids = array();

    if (!empty($values['cq_collectibles_for_sale_ids']))
    {
      $collectibles_for_sale_ids = cqFunctions::explode(',', $values['cq_collectibles_for_sale_ids']);

      // Get some element of surprise
      shuffle($collectibles_for_sale_ids);

      /** @var $q CollectibleForSaleQuery */
      $q = CollectibleForSaleQuery::create()
        ->isForSale()
        ->filterByCollectibleId($collectibles_for_sale_ids, Criteria::IN)
        ->select('CollectibleId')
        ->addAscendingOrderByColumn(
          'FIELD(collectible_for_sale.collectible_id, ' . implode(',', $collectibles_for_sale_ids) . ')'
        );
      $collectibles_for_sale_ids = $q->find()->toArray();
    }
    if (!empty($values['cq_wp_post_ids']))
    {
      $wp_post_ids = cqFunctions::explode(',', $values['cq_wp_post_ids']);
    }

    $this->wp_post = $wp_post;
    $this->wp_post_ids = $wp_post_ids;
    $this->collectibles_for_sale_ids = $collectibles_for_sale_ids;

    return $this->wp_post ? sfView::SUCCESS : sfView::NONE;
  }

  public function executeWordPressFeaturedItemsPinterest()
  {
    /* @var $page integer */
    $page = (integer) $this->getRequestParameter('page', 1);

    /* @var $pager cqPropelModelPager */
    $pager = $this->getVar('pager');
    $pager->setPage($page);
    $pager->init();
    $this->pager = $pager;

    // if we are trying to get an out of bounds page
    if ($page > 1 && $page > $pager->getLastPage())
    {
      // return empty response
      return sfView::NONE;
    }

    return sfView::SUCCESS;
  }

  public function executeWordPressFeaturedItemsGrid()
  {
    /* @var $page integer */
    $page = (integer) $this->getRequestParameter('page', 1);

    /* @var $pager cqPropelModelPager */
    $pager = $this->getVar('pager');
    $pager->setPage($page);
    $pager->init();
    $this->pager = $pager;

    // if we are trying to get an out of bounds page
    if ($page > 1 && $page > $pager->getLastPage())
    {
      // return empty response
      return sfView::NONE;
    }

    return sfView::SUCCESS;
  }

  public function executeWordPressFeaturedItemsSlot1()
  {
    if (!$this->wp_post = wpPostQuery::create()->findOneById($this->getRequestParameter('id')))
    {
      return sfView::NONE;
    }

    return sfView::SUCCESS;
  }

}
