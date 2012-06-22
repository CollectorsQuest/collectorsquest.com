<?php

class marketplaceComponents extends cqFrontendComponents
{
  public function executeSidebarIndex()
  {
    if ($id = $this->getRequestParameter('id'))
    {
      $this->category = ContentCategoryQuery::create()->findOneById($id);
    }

    return sfView::SUCCESS;
  }

  public function executeSidebarCategories()
  {
    return sfView::SUCCESS;
  }

  public function executeDiscoverCollectiblesForSale()
  {
    $q = $this->getRequestParameter('q');
    $s = $this->getRequestParameter('s', 'most-popular');
    $p = $this->getRequestParameter('p', 1);

    // Initialize the $pager
    $pager = null;

    if (!empty($q) || $s != 'most-popular')
    {
      $query = array(
        'q' => $q,
        'filters' => array('has_thumbnail' => 1, 'uint1' => 1)
      );

      $query['sortby'] = 'date';
      $query['order'] = 'desc';

      switch ($s)
      {
        case 'under-100':
          $query['sortby'] = 'uint2';
          $query['order'] = 'desc';
          $query['filters']['uint2'] = array('max' => 10000);
          break;
        case '100-200':
          $query['sortby'] = 'uint2';
          $query['order'] = 'asc';
          $query['filters']['uint2'] = array('min' => 10100, 'max' => 20000);
          break;
        case 'over-250':
          $query['sortby'] = 'uint2';
          $query['order'] = 'asc';
          $query['filters']['uint2'] = array('min' => 25000);
          break;
        case 'most-recent':
          $query['sortby'] = 'date';
          $query['order'] = 'desc';
          break;
        case 'most-popular':
        default:
          break;
      }

      $pager = new cqSphinxPager($query, array('collectibles'), 12);
    }
    else
    {
      /** @var $query wpPostQuery */
      $query = wpPostQuery::create()
        ->filterByPostType('marketplace_explore')
        ->filterByPostParent(0)
        ->orderByPostDate(Criteria::DESC);

      if (sfConfig::get('sf_environment') === 'prod')
      {
        $query->filterByPostStatus('publish');
      }

      /** @var $wp_post wpPost */
      if ($wp_post = $query->findOne())
      {
        $values = unserialize($wp_post->getPostMetaValue('_market_explore_items'));

        if (isset($values['cq_collectible_ids']))
        {
          $collectible_ids = explode(',', (string) $values['cq_collectible_ids']);
          $collectible_ids = array_map('trim', $collectible_ids);
          $collectible_ids = array_filter($collectible_ids);

          $query = CollectibleQuery::create()
            ->filterById($collectible_ids)
            ->haveThumbnail()
            ->useCollectibleForSaleQuery(null, Criteria::RIGHT_JOIN)
              ->isForSale()
            ->endUse()
            ->addAscendingOrderByColumn('FIELD(collectible.id, '. implode(',', $collectible_ids) .')');

          $pager = new PropelModelPager($query, 12);
        }

        $this->wp_post = $wp_post;
      }
    }

    if ($pager)
    {
      $pager->setPage($p);
      $pager->init();

      $this->pager = $pager;
      $this->url = sprintf(
        '@search_collectibles_for_sale?q=%s&s=%s&page=%d',
        $q, $s, $pager->getNextPage()
      );

      return sfView::SUCCESS;
    }

    return sfView::NONE;
  }
}
