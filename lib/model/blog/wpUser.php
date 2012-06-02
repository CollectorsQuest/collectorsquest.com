<?php

require 'lib/model/blog/om/BasewpUser.php';

class wpUser extends BasewpUser
{
  /**
   * @todo: To implement
   *
   * @param string $type
   * @return array|string
   */
  public function getTags($type = 'string')
  {
    $tags = array();

    return ($type == 'array') ? $tags : implode(', ', $tags);
  }

  public function getAvatarUrl($size = 'full')
  {
    $meta = unserialize($this->getUserMetaValue('simple_local_avatar'));

    if (isset($meta[$size]))
    {
      return $meta[$size];
    }

    return null;
  }

  public function getUserMetaValue($key)
  {
    /** @var $q wpPostMetaQuery */
    $q = wpUserMetaQuery::create()
      ->filterBywpUser($this)
      ->filterByMetaKey($key);

    /** @var $wp_post_meta wpPostMeta */
    $wp_user_meta = $q->findOne();

    return ($wp_user_meta) ? $wp_user_meta->getMetaValue() : null;
  }
}
