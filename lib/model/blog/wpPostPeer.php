<?php

require 'lib/model/blog/om/BasewpPostPeer.php';

class wpPostPeer extends BasewpPostPeer
{
  public static function retrieveByTags($tags = array(), $limit = 0)
  {
    $c = new Criteria();
    $c->setDistinct();
    $c->add(wpPostPeer::POST_TYPE, 'post');
    $c->addJoin(wpPostPeer::ID, wpTermRelationshipPeer::OBJECT_ID);
    $c->addJoin(wpTermRelationshipPeer::TERM_TAXONOMY_ID, wpTermTaxonomyPeer::TERM_TAXONOMY_ID);
    $c->addJoin(wpTermTaxonomyPeer::TERM_ID, wpTermPeer::TERM_ID);
    $c->add(wpTermTaxonomyPeer::TAXONOMY, 'post_tag');
    $c->add(wpTermPeer::NAME, $tags, Criteria::IN);
    $c->addDescendingOrderByColumn(wpPostPeer::POST_DATE);
    $c->setLimit($limit);

    return wpPostPeer::doSelect($c);
  }

  public static function getLatestPosts($limit = 10)
  {
    $c = new Criteria();
    $c->add(wpPostPeer::POST_STATUS, 'publish');
    $c->add(wpPostPeer::POST_TYPE, 'post');
    $c->addDescendingOrderByColumn(wpPostPeer::POST_DATE);
    $c->setLimit($limit);

    return wpPostPeer::doSelect($c);
  }
}
