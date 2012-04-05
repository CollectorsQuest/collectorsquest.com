<?php

class searchComponents extends cqFrontendComponents
{
  public function executeSidebar()
  {
    $q = $this->getRequestParameter('q', $this->getVar('q'));
    $t = $this->getRequestParameter('t', $this->getVar('t'));
    $s = $this->getRequestParameter('s', $this->getVar('s'));

    // Current URL
    $url = new IceTypeUrl($this->getRequest()->getUri());

    $this->sortby = array(
      'most-relevant' => array(
        'name' => 'Most Relevant',
        'active' => 'most-relevant' == $s || empty($s),
        'route' => (string) $url->replaceQueryString('s', 'most-relevant')
      ),
      'most-recent' => array(
        'name' => 'Most Recent',
        'active' => 'most-recent' == $s,
        'route' => (string) $url->replaceQueryString('s', 'most-recent')
      ),
      'most-popular' => array(
        'name' => 'Most Popular',
        'active' => 'most-popular' == $s,
        'route' => (string) $url->replaceQueryString('s', 'most-popular')
      ),
    );

    $types_selected = explode(',', $t);
    $types_selected = array_filter($types_selected);

    $this->types = array(
      'everything' => array(
        'name' => 'Everything',
        'count' => -1,
        'active' => empty($types_selected),
        'route' => '@search?q='. $q
      ),
      'collection' => array(
        'name' => 'Collections',
        'count' => 0,
        'active' => in_array('collection', $types_selected),
        'route' => '@search_collections?q='. $q
      ),
      'collector' => array(
        'name' => 'Collectors',
        'count' => 0,
        'active' => in_array('collector', $types_selected),
        'route' => '@search_collectors?q='. $q
      ),
      'collectible' => array(
        'name' => 'Collectibles',
        'count' => 0,
        'active' => in_array('collectible', $types_selected),
        'route' => '@search_collectibles?q='. $q
      ),
      'wp_post' => array(
        'name' => 'Blog Articles',
        'count' => 0,
        'active' => in_array('wp_post', $types_selected),
        'route' => '@search_blog?q='. $q
      ),
      'video' => array(
        'name' => 'Videos',
        'count' => 0,
        'active' => in_array('video', $types_selected),
        'route' => '@search_videos?q='. $q
      ),
    );

    $query = array(
      'q' => $q,
      'groupby' => 'object_type'
    );
    $matches = cqSphinxPager::search($query, array(), 'matches');

    foreach ($matches as $match)
    {
      $this->types[$match['attrs']['object_type']]['count'] = $match['attrs']['@distinct'];
    }

    $q = CollectionCategoryQuery::create()
      ->filterById(0, Criteria::NOT_EQUAL)
      ->filterByParentId(0, Criteria::EQUAL)
      ->orderByName(Criteria::ASC)
      ->limit(30);
    $this->categories = $q->find();

    return sfView::SUCCESS;
  }
}
