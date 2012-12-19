<?php

/**
 * @param     string $slug
 * @return    CollectibleForSale
 */
function collectible_for_sale_by_slug($slug)
{
  return CollectibleForSaleQuery::create()
    ->useCollectibleQuery()
      ->filterBySlug($slug)
    ->endUse()
    ->findOne();
}

