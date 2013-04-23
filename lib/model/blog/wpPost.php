<?php

require 'web/blog/wp-includes/functions.php';
require 'lib/model/blog/om/BasewpPost.php';

class wpPost extends BasewpPost
{
  public
    $_multimedia = array(),
    $_counts = array();

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

  public function getPostExcerpt($length = 0, $truncate_string = '...')
  {
    $excerpt = parent::getPostExcerpt();

    if (empty($excerpt) && $length > 0)
    {
      $excerpt = strip_tags($this->getPostContentStripped());
    }

    return $length > 0 ? cqStatic::truncateText($excerpt, $length, $truncate_string, true) : $excerpt;
  }

  public function getPostTitle()
  {
    return wpPostPeer::sanitize(parent::getPostTitle());
  }

  public function getSlug()
  {
    return $this->getPostName();
  }

  public function getPostContent()
  {
    return wpPostPeer::sanitize(parent::getPostContent());
  }

  public function getPostContentStripped()
  {
    return wpPostPeer::stripShortcodes($this->getPostContent());
  }

  public function getPostThumbnail($size = 'original')
  {
    if ($thumbnail_id = $this->getPostMetaValue('_thumbnail_id'))
    {
      $q = wpPostMetaQuery::create()
        ->filterByPostId($thumbnail_id);

      if ($size !== 'original') {
        $q->filterByMetaKey('_wp_attachment_metadata');
      } else {
        $q->filterByMetaKey('_wp_attached_file');
      }

      /** @var $wp_post_meta wpPostMeta */
      if ($wp_post_meta = $q->findOne())
      {
        if ($size !== 'original')
        {
          $data = unserialize($wp_post_meta->getMetaValue());

          if (isset($data['sizes'][$size]))
          {
            return sprintf(
              '/uploads/blog/%s/%s', dirname($data['file']), $data['sizes'][$size]['file']
            );
          }
        }
        else
        {
          return '/uploads/blog/' . $wp_post_meta->getMetaValue();
        }
      }
    }

    return null;
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

    return ($wp_post_meta) ? maybe_unserialize($wp_post_meta->getMetaValue()) : null;
  }

  /**
   * @param     string $key
   * @param     mixed $value
   * @return    integer Updated records
   */
  public function setPostMetaValue($key, $value)
  {
    return wpPostMetaQuery::create()
       ->filterBywpPost($this)
       ->filterByMetaKey($key)
       ->update(array('MetaValue' => maybe_serialize($value)));
  }

  /**
   * This is needed for IceMultimediaBehavior to work correctly
   *
   * @param string $name
   * @return null
   */
  public function getEblobElement($name)
  {
    return null;
  }

  /**
   * @param  string  $name
   * @param  null    $element
   *
   * @return boolean
   */
  public function setEblobElement($name, $element = null)
  {
    return false;
  }

  /**
   * For each Multimedia that is added to the Advert, this method will be called
   * to take care of creating the right thumnail sizes
   *
   * @param  iceModelMultimedia  $multimedia
   * @param  array $options
   *
   * @throws InvalidArgumentException
   * @return void
   */
  public function createMultimediaThumbs(iceModelMultimedia $multimedia, $options = array())
  {
    /**
     * We need to have the four main thumbnails available as soon as the object is saved so
     * we make sure they are not put on the job queue
     */
    $multimedia->makeThumb(300, 225, 'top', false);
    $multimedia->makeCustomThumb(270, 270, '270x270', 'center', false);
  }
}

sfPropelBehavior::add('wpPost', array('IceMultimediaBehavior'));
