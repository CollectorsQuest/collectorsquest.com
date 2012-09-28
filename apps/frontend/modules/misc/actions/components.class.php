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
      $collectibles_for_sale_ids = self::getValues($values['cq_collectibles_for_sale_ids']);

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
      $wp_post_ids = self::getValues($values['cq_wp_post_ids']);
    }

    $this->wp_post = $wp_post;
    $this->wp_post_ids = $wp_post_ids;
    $this->collectibles_for_sale_ids = $collectibles_for_sale_ids;

    return $this->wp_post ? sfView::SUCCESS : sfView::NONE;
  }

  /**
   * @param  $value string
   * @return array
   */
  private function getValues ($value)
  {
    $value = explode(',', $value);
    $value = array_map('trim', $value);
    $value = array_filter($value);

    return $value;
  }
}
