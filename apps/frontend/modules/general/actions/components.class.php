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
    $wp_posts = $q->limit(15)->find();

    foreach ($wp_posts as $wp_post)
    if ($image = $wp_post->getPostThumbnail())
    {
      $this->carousels[] = array(
        'image' => $image,
        'title' => $wp_post->getPostTitle(),
        'content' => $wp_post->getPostContent()
      );
    }

    // Make sure we display only 8 items
    $this->carousels = array_slice($this->carousels, 0, 8);

    return sfView::SUCCESS;
  }

  public function executeSidebarIndex()
  {
    return sfView::SUCCESS;
  }
}
