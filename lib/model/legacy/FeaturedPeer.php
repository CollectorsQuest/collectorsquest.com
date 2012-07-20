<?php

require 'lib/model/legacy/om/BaseFeaturedNestedSetPeer.php';

class FeaturedPeer extends BaseFeaturedNestedSetPeer
{
  const TYPE_HOMEPAGE_VIDEOS            = 1;
  const TYPE_SPOTLIGHT_VIDEOS           = 2;
  const TYPE_VIDEO_PAGE                 = 3;
  const TYPE_SPOTLIGHT_COLLECTIBLES     = 4;
  const TYPE_SPOTLIGHT_RESOURCES        = 5;
  const TYPE_FEATURED_WEEK              = 6;
  const TYPE_FEATURED_WEEK_COLLECTORS   = 7;
  const TYPE_COLLECTOR_INTERVIEW        = 8;
  const TYPE_FEATURED_WEEK_COLLECTIONS  = 9;

  public static function getCurrentFeatured($type)
  {
    $c = new Criteria();
    $c->add(self::FEATURED_TYPE_ID, $type);
    $c->add(self::TREE_LEFT, 1);
    $c->add(self::START_DATE, date('Y-m-d'), Criteria::LESS_EQUAL);
    $c->add(self::END_DATE, date('Y-m-d'), Criteria::GREATER_EQUAL);
    $c->add(self::IS_ACTIVE, true);
    $c->addDescendingOrderByColumn(self::END_DATE);

    return self::doSelectOne($c);
  }

  public static function getLatestFeatured($type)
  {
    $c = new Criteria();
    $c->add(self::FEATURED_TYPE_ID, $type);
    $c->add(self::TREE_LEFT, 1);
    $c->add(self::END_DATE, date('Y-m-d'), Criteria::LESS_EQUAL);
    $c->add(self::IS_ACTIVE, true);
    $c->addDescendingOrderByColumn(self::END_DATE);

    return self::doSelectOne($c);
  }

  public static function getPastFeatured($type, $limit = 0)
  {
    $c = new Criteria();
    $c->add(self::FEATURED_TYPE_ID, $type);
    $c->add(self::TREE_LEFT, 1);
    $c->add(self::END_DATE, date('Y-m-d'), Criteria::LESS_THAN);
    $c->add(self::IS_ACTIVE, true);
    $c->setLimit($limit);
    $c->addDescendingOrderByColumn(self::END_DATE);

    return self::doSelect($c);
  }

  public static function getObjectForFeaturedWeek($params)
  {
    return FeaturedPeer::retrieveByPK($params['id']);
  }
}
