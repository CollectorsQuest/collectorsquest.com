<?php

require 'lib/model/blog/om/BasewpPost.php';

class wpPost extends BasewpPost
{
  public function getRelatedCollections($limit = 2)
  {
    return CollectionPeer::getPopularByTag($this->getTags('array'), $limit);
  }

  public function getRelatedVideos($limit = 2)
  {
    return VideoPeer::retrieveByTags($this->getTags('array'), $limit);
  }

  public function getPostUrl()
  {
    return 'http://'. sfConfig::get('app_domain_name') .'/blog/'.$this->getPostDate('Y/m/d').'/'.$this->getPostName();
  }

  public function getPostExcerpt($length = 250, $truncate_string = '...')
  {
    $excerpt = parent::getPostExcerpt();

    if (empty($excerpt))
    {
      $excerpt = strip_tags(str_replace(
        array('[/caption]', '[caption', ']'), array('</caption>', '<caption', '>'), $this->getPostContent()
      ));
    }

    return cqStatic::truncateText($excerpt, $length, $truncate_string);
  }

  public function getTags($type = 'string')
  {
    $c = new Criteria();
    $c->addSelectColumn(wpTermPeer::NAME);
    $c->addJoin(wpTermPeer::TERM_ID, wpTermTaxonomyPeer::TERM_ID);
    $c->addJoin(wpTermTaxonomyPeer::TERM_TAXONOMY_ID, wpTermRelationshipPeer::TERM_TAXONOMY_ID);
    $c->add(wpTermTaxonomyPeer::TAXONOMY, 'post_tag');
    $c->add(wpTermRelationshipPeer::OBJECT_ID, $this->getId());

    $results = wpTermPeer::doSelectStmt($c);

    $tags = array();
    while ($row = $results->fetch(PDO::FETCH_NUM))
    {
     $tags[] = $row[0];
    }

    return ($type == 'array')?$tags:implode(', ', $tags);
  }
}
