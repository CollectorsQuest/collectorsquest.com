<?php

class categoriesActions extends cqFrontendActions
{

  public function preExecute()
  {
    parent::preExecute();

    SmartMenu::setSelected('header', 'collections');
  }

  public function executeIndex()
  {
    $this->level1_categories = ContentCategoryQuery::create()
      ->childrenOfRoot()
      ->hasCollections()
      ->orderBy('Name')
      ->find();

    // find the category "Other"
    $this->category_other = ContentCategoryQuery::create()
      ->findOneById('3560');

    return sfView::SUCCESS;
  }

  public function executeCategory(sfWebRequest $request)
  {
    /** @var $category ContentCategory */
    $category = $this->getRoute()->getObject();

    // Make the category available in the sidebar action
    $this->setComponentVar('category', $category, 'sidebarCategory');

    $this->collectors_question = null;

    /**
     * This is functionality for Collector Questions as described in:
     * @see: https://basecamp.com/1759305/projects/353141-collectorsquest-com/todos/5134822-the-collectors
     *
     * NOTE: This is disabled as of now because it is not used
     */
    if (false && $request->getParameter('page', 1) == 1)
    {
      /** @var $q wpPostQuery */
      $q = wpPostQuery::create()
        ->filterByPostType('collectors_question')
        ->filterByPostParent(0)
        ->orderByPostDate(Criteria::DESC)
        ->joinwpPostMeta(null, Criteria::RIGHT_JOIN)
        ->usewpPostMetaQuery()
          ->filterByMetaKey('cq_content_category_id')
          ->filterByMetaValue($category->getId())
        ->endUse();

      if (sfConfig::get('sf_environment') === 'prod')
      {
        $q->filterByPostStatus('publish');
      }

      /** @var $wp_posts wpPost[] */
      if ($wp_posts = $q->limit(5)->find())
      {
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
    }

    $q = FrontendCollectorCollectionQuery::create()
       ->hasThumbnail()
       ->hasCollectibles()
       ->filterByContentCategoryWithDescendants($category)
       ->orderByUpdatedAt(Criteria::DESC);

    $pager = new PropelModelPager($q, $this->collectors_question !== null ? 16 : 36);
    $pager->setPage($request->getParameter('page', 1));
    $pager->init();

    $this->pager = $pager;

    $this->category = $category;

    return sfView::SUCCESS;
  }
}
