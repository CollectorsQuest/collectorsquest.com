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
    $s = $this->getRequestParameter('s', 'most-recent');
    $p = $this->getRequestParameter('p', 1);

    // Initialize the $pager
    $pager = null;

    if (!empty($q) || $s != 'most-recent')
    {
      $query = array(
        'q' => $q,
        'filters' => array('uint1' => 1)
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
        default:
          break;
      }

      $pager = new cqSphinxPager($query, array('collectibles'), 12);
    }
    else
    {
      $query = wpPostQuery::create()
        ->filterByPostType('marketplace_explore')
        ->filterByPostStatus('publish')
        ->orderByPostDate(Criteria::DESC);

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
            ->addAscendingOrderByColumn('FIELD(id, '. implode(',', $collectible_ids) .')');

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
      $this->url = '@search_collectibles_for_sale?q='. $q . '&s='. $s .'&page='. $pager->getNextPage();

      return sfView::SUCCESS;
    }

    return sfView::NONE;
  }
}
