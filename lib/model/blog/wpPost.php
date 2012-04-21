<?php

require 'lib/model/blog/om/BasewpPost.php';

class wpPost extends BasewpPost
{
  public function getRelatedCollections($limit = 2)
  {
    return CollectorCollectionPeer::getPopularByTag($this->getTags('array'), $limit);
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
      $excerpt = strip_tags($this->getPostContentStripped());
    }

    return cqStatic::truncateText($excerpt, $length, $truncate_string);
  }

  public function getPostTitle()
  {
    return wpPostPeer::sanitize(parent::getPostTitle());
  }

  public function getPostContent()
  {
    return wpPostPeer::sanitize(parent::getPostContent());
  }

  public function getPostContentStripped()
  {
    return wpPostPeer::stripShortcodes($this->getPostContent());
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

    return ($type == 'array') ? $tags : implode(', ', $tags);
  }

  public function getPlainPostContent()
  {
    return trim(strip_tags($this->getPostContentStripped()));
  }

  public function countPostContentWords()
  {
    if ('' == $this->getPlainPostContent())
    {
      return 0;
    }
    else
    {
      return count(explode(' ', $this->getPlainPostContent()));
    }
  }

  public function countPostContentChars()
  {
    return mb_strlen(str_replace(' ', '', $this->getPlainPostContent()), 'utf-8');
  }

  public function getPostMetaValue($key)
  {
    /** @var $q wpPostMetaQuery */
    $q = wpPostMetaQuery::create()
       ->filterBywpPost($this)
       ->filterByMetaKey($key);

    /** @var $wp_post_meta wpPostMeta */
    $wp_post_meta = $q->findOne();

    return ($wp_post_meta) ? $wp_post_meta->getMetaValue() : null;
  }
}
