<?php

class collectorsActions extends cqFrontendActions
{

  /**
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeIndex(sfWebRequest $request)
  {
    /** @var $q FrontendCollectorQuery */
    $q = FrontendCollectorQuery::create();

    $sortBy = $request->getParameter('sort', 'latest');
    $type = $request->getParameter('type', 'collectors');

    if ('sellers' == $type)
    {
      $q->filterByUserType(CollectorPeer::TYPE_SELLER, Criteria::EQUAL);
    }

    switch ($sortBy)
    {
      case 'most-popular':
        $q->joinCollectorCollection(null, Criteria::LEFT_JOIN)

          //  ->useCollectorCollectionQuery()
          //  ->filterByNumItems(10, Criteria::GREATER_EQUAL)
          //  ->endUse()

          ->joinCollectorProfile()
          ->withColumn('SUM(CollectorCollection.NumViews)', 'TotalCollectionsViews')
          ->groupBy('CollectorCollection.CollectorId')
          ->orderBy('CollectorCollection.NumViews', Criteria::DESC);

        $this->getResponse()->addMeta(
          'title',
          'Popular Collectors & Collections | Collectors Quest'
        );
        $this->getResponse()->addMeta(
          'description',
          sprintf(
            'Collectors Quest is an interactive community and marketplace for the passionate collector.
            Come join members like %s, and share your collections today!', $q->findOne()
          )
        );
        break;

      case 'near-you':
        $profile = $this->getUser()->getCollector()->getProfile();
        if ($profile && 'US' == $profile->getCountryIso3166())
        {
          $pks = CollectorPeer::retrieveByDistance($profile->getZipPostal(), 50, true);
          $pks = array_diff($pks, array(0 => $this->getUser()->getId()));

          $q->filterById($pks, Criteria::IN)
              ->orderById(Criteria::DESC);
        }
        break;

      case 'sellers':
        $q->orderById(Criteria::DESC);
        break;

      case 'latest':
      default:
        $q->orderById(Criteria::DESC);
        break;
    }

    $pager = new cqPropelModelPager($q, 36);
    $pager->setPage($request->getParameter('page', 1));
    $pager->init();

    $this->pager = $pager;
    $this->display = $this->getUser()->getAttribute('display', 'grid', 'collectors');
    $this->type = $type;
    $this->sortBy = $sortBy;

    return sfView::SUCCESS;
  }

}
