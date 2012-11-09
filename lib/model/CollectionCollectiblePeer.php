<?php

require 'lib/model/om/BaseCollectionCollectiblePeer.php';

class CollectionCollectiblePeer extends BaseCollectionCollectiblePeer
{
  /**
   * @static
   * @param  array  $parameters
   *
   * @return CollectionCollectible
   */
  public static function getObjectForRoute($parameters)
  {
    $collectible = null;
    $parameters['id'] = str_replace(array('.html', '.htm'), '', $parameters['id']);

    $q = FrontendCollectionCollectibleQuery::create()
      ->filterByCollectibleId($parameters['id'])
      ->orderByCollectibleId(Criteria::DESC);

    // If the Collectible has only one collection, do not even check here
    if ($q->count() > 1 && preg_match('/-c(\d+)$/i', @$parameters['slug'], $m))
    {
      $q->filterByCollectionId((int) $m[1]);
    }

    return $collectible = $q->findOne();
  }
}
