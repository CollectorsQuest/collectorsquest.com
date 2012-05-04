<?php

class categoriesActions extends cqFrontendActions
{
  public function executeIndex()
  {
    return sfView::SUCCESS;
  }

  public function executeCategory(sfWebRequest $request)
  {
    $this->category = $this->getRoute()->getObject();
    $this->collectors_question = null;

    if ($request->getParameter('page', 1) == 1)
    {
      $q = wpPostQuery::create()
        ->filterByPostType('collectors_question')
        ->filterByPostStatus('publish')
        ->joinwpPostMeta(null, Criteria::RIGHT_JOIN)
        ->usewpPostMetaQuery()
          ->filterByMetaKey('cq_content_category_id')
          ->filterByMetaValue($this->category->getId())
        ->endUse()
        ->orderByPostDate(Criteria::DESC);

      /** @var $wp_posts wpPost[] */
      if ($wp_posts = $q->limit(5)->find())
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
            $this->collectors_question = array(
              'title' => $wp_post->getPostTitle(),
              'content' => $wp_post->getPostContent(),
              'image' => '/uploads/blog/' . $wp_post_meta->getMetaValue()
            );

            break;
          }
        }
      }
    }

    $q = CollectorCollectionQuery::create()
       ->filterByContentCategory($this->category)
       ->orderByUpdatedAt(Criteria::DESC);

    $pager = new PropelModelPager($q, $this->collectors_question !== null ? 16 : 36);
    $pager->setPage($request->getParameter('page', 1));
    $pager->init();

    $this->pager = $pager;

    return sfView::SUCCESS;
  }
}
