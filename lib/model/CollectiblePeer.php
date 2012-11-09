<?php

require 'lib/model/om/BaseCollectiblePeer.php';

class CollectiblePeer extends BaseCollectiblePeer
{

  /**
   * @static
   * @param  array  $parameters
   *
   * @return Collectible|CollectionCollectible
   */
  public static function getObjectForRoute($parameters)
  {
    $collectible = null;
    $parameters['id'] = str_replace(array('.html', '.htm'), '', $parameters['id']);

    if (preg_match('/-c(\d+)$/i', $parameters['slug'], $m))
    {
      $q = FrontendCollectionCollectibleQuery::create()
         ->filterByCollectibleId($parameters['id'])
         ->filterByCollectionId((int) $m[1]);

      $collectible = $q->findOne();
    }

    return $collectible ? $collectible : self::retrieveByPk($parameters['id']);
  }

  public static function getLatest($limit = 18)
  {
    $c = new Criteria();
    $c->addDescendingOrderByColumn(self::ID);
    $c->setLimit($limit);

    return self::doSelect($c);
  }

  public static function getPopularByTag($tag, $limit = 6)
  {
    $c = new Criteria();
    $c->add(iceModelTagPeer::NAME, $tag);
    $c->addJoin(iceModelTaggingPeer::TAG_ID, iceModelTagPeer::ID);
    $c->add(iceModelTaggingPeer::TAGGABLE_MODEL, 'Collectible');
    $c->addJoin(self::ID, iceModelTaggingPeer::TAGGABLE_ID, Criteria::LEFT_JOIN);
    $c->setLimit($limit);

    return self::doSelect($c);
  }

  public static function getPopularTags($limit = 50)
  {
    $c = new Criteria();
    $c->add(iceModelTagPeer::NAME, 'CHAR_LENGTH('.iceModelTagPeer::NAME.') > 2', Criteria::CUSTOM);
    $c->setLimit($limit);

    return iceModelTagPeer::getPopulars($c, array('model' => 'Collectible'));
  }

  public static function updateItemIsForSale($snCollectibleId, $bIsForSale = true)
  {
    $omCollectible = self::retrieveByPK($snCollectibleId);
    $omCollectible->setIsForSale($bIsForSale);

    try
    {
      $omCollectible->save();
    }
    catch (PropelException $e)
    {
      return false;
    }

    return $omCollectible;
  }

}
