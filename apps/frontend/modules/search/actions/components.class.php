<?php

class searchComponents extends cqFrontendComponents
{
  public function executeSidebar()
  {
    $q = $this->getRequestParameter('q', $this->getVar('q'));
    $t = $this->getRequestParameter('t', $this->getVar('t'));

    $types_selected = explode(',', $t);
    $this->types = array(
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
        'name' => 'News Articles',
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
