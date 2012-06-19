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
    $q = CollectorQuery::create();

    $sortBy = $request->getParameter('sort', 'latest');
    $type = $request->getParameter('type', 'collectors');
    $q->filterByUserType('sellers' == $type ? 'Seller' : 'Collector', Criteria::EQUAL);

    switch ($sortBy)
    {
      case 'most-popular':
        $q->joinCollectorCollection(null, Criteria::LEFT_JOIN)
            ->withColumn('SUM(CollectorCollection.NumViews)', 'TotalCollectionsViews')
            ->groupBy('CollectorCollection.CollectorId')

//            ->useCollectorCollectionQuery()
//            ->filterByNumItems(10, Criteria::GREATER_EQUAL)
//            ->endUse()

            ->joinCollectorProfile()

            ->orderBy('CollectorCollection.NumViews', Criteria::DESC)
        ;
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

    $pager = new PropelModelPager($q, 36);
    $pager->setPage($request->getParameter('page', 1));
    $pager->init();

    $this->pager = $pager;
    $this->display = $this->getUser()->getAttribute('display', 'grid', 'collectors');
    $this->type = $type;
    $this->sortBy = $sortBy;

    return sfView::SUCCESS;
  }

}
