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
      'collectible_for_sale' => array(
        'name' => 'Items for Sale',
        'count' => 0,
        'active' => in_array('collectible_for_sale', $types_selected),
        'route' => '@search_collectibles_for_sale?q='. $q
      ),
      'wp_post' => array(
        'name' => 'Blog Posts',
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

    if (SF_ENV !== 'dev')
    {
      try
      {
        $magnify = cqStatic::getMagnifyClient();
        $results = $magnify->getContent()->find($q);
        $this->types['video']['count'] = $results->getTotalResults();
      }
      catch (MagnifyException $e)
      {
        $this->types['video']['count'] = 0;
      }
    }

    /**
     * Get the number of matches for everything but Collectibles for Sale
     */
    $query = array(
      'q' => $q,
      'groupby' => 'object_type',
      'filters' => array('has_thumbnail' => 'yes', 'is_public' => true)
    );
    $matches = cqSphinxPager::search($query, array(), 'matches');

    foreach ($matches as $match)
    {
      $this->types[$match['attrs']['object_type']]['count'] = $match['attrs']['@distinct'];
    }

    /**
     * Get the number of matches for Collectibles for Sale
     */
    $query = array(
      'q' => $q,
      'filters' => array(
        'object_type' => 'collectible',
        'has_thumbnail' => 'yes',
        'is_public' => true,
        'uint1' => 1
      )
    );
    $this->types['collectible_for_sale']['count'] = cqSphinxPager::search(
      $query, array('collectibles'), 'total'
    );

    return sfView::SUCCESS;
  }
}
