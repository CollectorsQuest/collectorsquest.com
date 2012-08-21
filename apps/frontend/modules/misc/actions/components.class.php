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
    $values = unserialize($wp_post->getPostMetaValue('_featured_items'));

    // Initialize the arrays
    $collectibles_for_sale_ids = $wp_post_ids = array();

    if (!empty($values['cq_collectibles_for_sale_ids']))
    {
      $collectibles_for_sale_ids = explode(',', $values['cq_collectibles_for_sale_ids']);
      $collectibles_for_sale_ids = array_map('trim', $collectibles_for_sale_ids);
      $collectibles_for_sale_ids = array_filter($collectibles_for_sale_ids);

      // Get some element of surprise
      array_shift($collectibles_for_sale_ids);

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
      $wp_post_ids = explode(',', $values['cq_wp_post_ids']);
      $wp_post_ids = array_map('trim', $wp_post_ids);
      $wp_post_ids = array_filter($wp_post_ids);
    }

    $this->wp_post = $wp_post;
    $this->wp_post_ids = $wp_post_ids;
    $this->collectibles_for_sale_ids = $collectibles_for_sale_ids;

    return $this->wp_post ? sfView::SUCCESS : sfView::NONE;
  }
}
