<?php

class marketplaceActions extends cqFrontendActions
{

  public function preExecute()
  {
    parent::preExecute();

    SmartMenu::setSelected('header', 'marketplace');
  }

  public function executeIndex()
  {
    $this->redirectIf(
      cqGateKeeper::open('holiday_marketplace', 'page'),
      '@marketplace_holiday'
    );

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

      $values = $wp_post->getPostMetaValue('_market_featured_items');

      for ($i = 1; $i <= 6; $i++)
      {
        if (isset($values['cq_collectible_id_'. $i]))
        {
          /* @var $q CollectibleForSaleQuery */
          $q = FrontendCollectibleForSaleQuery::create()
             ->isForSale();

          /* @var $collectible_for_sale CollectibleForSale */
          $collectible_for_sale = $q->findOneByCollectibleId(trim($values['cq_collectible_id_'. $i]));

          if ($collectible_for_sale)
          {
            $collectibles_for_sale[$i] = $collectible_for_sale;

            if (isset($values['cq_collectible_text_'. $i]))
            {
              $collectibles_for_sale_text[$i] = trim($values['cq_collectible_text_'. $i]);
            }
          }
        }
        if (sizeof($collectibles_for_sale) == 3)
        {
          break;
        }
      }

      $this->collectibles_for_sale = $collectibles_for_sale;
      $this->collectibles_for_sale_text = $collectibles_for_sale_text;
      $this->wp_post = $wp_post;
    }

    // Set Canonical Url meta tag
    $this->getResponse()->setCanonicalUrl($this->generateUrl('marketplace'));

    return sfView::SUCCESS;
  }

  public function executeHoliday(cqWebRequest $sf_request)
  {
    $this->categories = ContentCategoryQuery::create()
      ->filterById(array(2, 402, 674, 1767, 1367, 1425, 1677, 1755, 1604, 3043))
      ->hasCollectiblesForSale()
      ->orderByName(Criteria::ASC)
      ->find();

    // Set Canonical Url meta tag
    $this->getResponse()->setCanonicalUrl($this->generateUrl('marketplace'));

    return $sf_request->isMobileLayout() ? 'Mobile' : sfView::SUCCESS;
  }

  public function executeBrowse(sfWebRequest $request)
  {
    /** @var $content_category ContentCategory */
    $content_category = $this->getRoute()->getObject();

    /** @var $q FrontendCollectibleForSaleQuery */
    $q = FrontendCollectibleForSaleQuery::create();

     $q->filterByContentCategoryWithDescendants($content_category)
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

    // calculate how many rows of collectibles will be on the page
    $collectible_rows = count($pager->getResults());
    $collectible_rows = $collectible_rows % 3 == 0 ? intval($collectible_rows / 3) : intval($collectible_rows / 3 + 1);

    $this->pager = $pager;
    $this->content_category = $content_category;
    $this->collectible_rows = $collectible_rows;

    // Set Canonical Url meta tag
    $this->getResponse()->setCanonicalUrl(
      $this->generateUrl('marketplace_category_by_slug', array('sf_subject' => $content_category))
    );

    return sfView::SUCCESS;
  }

  public function executeCategories()
  {
    if (cqGateKeeper::locked('expose_market_categories'))
    {
      $this->redirect('@marketplace', 302);
    }

    $this->categories = ContentCategoryQuery::create()
      ->hasCollectiblesForSale()
      ->orderBy('Name')
      ->find();

    return sfView::SUCCESS;
  }
}
