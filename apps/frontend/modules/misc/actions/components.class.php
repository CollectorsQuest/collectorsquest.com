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
          'FIELD(collectible_id, ' . implode(',', $collectibles_for_sale_ids) . ')'
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

    /**
     * Action GuideSocialLogin
     *
     * @param sfWebRequest $request
     *
     * @return string
     */
    public function executeGuide_social_login(sfWebRequest $request)
    {
        $providers = CollectorIdentifierQuery::create()
            ->filterByCollector($this->getCollector())
            ->find()
            ->toKeyValue('Id', 'Provider');

        $this->setVar('excludeProviders', $providers);
    }

}
