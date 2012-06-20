<?php

class marketplaceActions extends cqFrontendActions
{

  public function preExecute()
  {
    parent::preExecute();

    SmartMenu::setSelected('header_main_menu', 'marketplace');
  }

  public function executeIndex()
  {
    /** @var $q wpPostQuery */
    $q = wpPostQuery::create()
      ->filterByPostType('marketplace_featured')
      ->filterByPostParent(0)
      ->orderByPostDate(Criteria::DESC);

    if (sfConfig::get('sf_environment') === 'prod')
    {
      $q->filterByPostStatus('publish');
    }

    /** @var $wp_post wpPost */
    if ($wp_post = $q->findOne())
    {
      $collectibles_for_sale = array();
      $collectibles_for_sale_text = array();

      $values = unserialize($wp_post->getPostMetaValue('_market_featured_items'));

      for ($i = 1; $i <= 3; $i++)
      if (isset($values['cq_collectible_id_'. $i]) && isset($values['cq_collectible_text_'. $i]))
      {
        $collectibles_for_sale[$i] = CollectibleForSaleQuery::create()
          ->findOneByCollectibleId(trim($values['cq_collectible_id_'. $i]));

        $collectibles_for_sale_text[$i] = trim($values['cq_collectible_text_'. $i]);
      }

      $this->collectibles_for_sale = $collectibles_for_sale;
      $this->collectibles_for_sale_text = $collectibles_for_sale_text;
      $this->wp_post = $wp_post;
    }


    return sfView::SUCCESS;
  }

  public function executeBrowse(sfWebRequest $request)
  {
    $content_category = $this->getRoute()->getObject();

    $q = CollectibleForSaleQuery::create()
       ->filterByContentCategoryWithDescendants($content_category)
       ->isForSale()
       ->orderByUpdatedAt(Criteria::DESC);

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

    $pager = new PropelModelPager($q, 18);
    $pager->setPage($this->getRequestParameter('page', 1));
    $pager->init();

    $this->pager = $pager;
    $this->content_category = $content_category;

    return sfView::SUCCESS;
  }

  public function executeCategories()
  {
    $this->level1_categories = ContentCategoryQuery::create()
      ->childrenOfRoot()
      ->withCollectiblesForSale()
      ->orderBy('Name')
      ->find();

    return sfView::SUCCESS;
  }
}
