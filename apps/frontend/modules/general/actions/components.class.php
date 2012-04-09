<?php

class generalComponents extends cqFrontendComponents
{
  public function executeIndexCarousel()
  {
    $this->carousels = array();

    $q = wpPostQuery::create()
       ->filterByPostType('homepage_carousel')
       ->filterByPostStatus('publish')
       ->orderByPostDate(Criteria::DESC);

    /** @var $wp_posts wpPost[] */
    $wp_posts = $q->limit(10)->find();

    foreach ($wp_posts as $wp_post)
    {
      if ($thumbnail_id = $wp_post->getPostMetaValue('_thumbnail_id'))
      {
        $q = wpPostMetaQuery::create()
           ->filterByPostId($thumbnail_id)
           ->filterByMetaKey('_wp_attached_file');

        /** @var $wp_post_meta wpPostMeta */
        if ($wp_post_meta = $q->findOne())
        {
          $this->carousels[] = array(
            'image' => '/uploads/blog/' . $wp_post_meta->getMetaValue(),
            'title' => $wp_post->getPostTitle(),
            'content' => $wp_post->getPostContent()
          );
        }
      }
    }

    // Make sure we display only 5 items
    $this->carousels = array_slice($this->carousels, 0, 5);

    return sfView::SUCCESS;
  }

  public function executeSidebarIndex()
  {
    return sfView::SUCCESS;
  }
}
