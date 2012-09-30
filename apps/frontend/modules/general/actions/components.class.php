<?php

class generalComponents extends cqFrontendComponents
{
  public function executeIndexCarousel()
  {
    $this->carousels = array();

    /** @var $q wpPostQuery */
    $q = wpPostQuery::create()
       ->filterByPostType('homepage_carousel')
       ->filterByPostParent(0)
       ->orderByPostDate(Criteria::DESC);

    if (sfConfig::get('sf_environment') === 'prod')
    {
      $q->filterByPostStatus('publish');
    }

    /** @var $wp_posts wpPost[] */
    $wp_posts = $q->limit(15)->find();

    foreach ($wp_posts as $wp_post)
    if ($image = $wp_post->getPostThumbnail('original'))
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
    /** @var $q wpPostQuery */
    $q = wpPostQuery::create()
      ->filterByPostType('cms_slot')
      ->filterByPostStatus('publish')
      ->filterByPostExcerpt('c4bf2d50-9daa-11e1-a8b0-0800200c9a66')
      ->orderByPostDate(Criteria::DESC);

    $this->cms_slot1 = $q->findOne();

    return sfView::SUCCESS;
  }
}
