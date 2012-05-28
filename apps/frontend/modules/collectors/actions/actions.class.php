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
        $q->useCollectorCollectionQuery()
          ->filterByNumItems(10, Criteria::GREATER_EQUAL)
          ->endUse()
          ->orderBy('Score', Criteria::DESC)
          ->distinct();
        break;

      case 'near-you':
        $profile = $this->getUser()->getCollector()->getProfile();
        if ($profile && 'US' == $profile->getCountryIso3166())
        {
          $pks = CollectorPeer::retrieveByDistance($profile->getZipPostal(), 50, true);
          $pks = array_diff($pks, array(0 => $this->getUser()->getId()));

          $q->filterById($pks, Criteria::IN)
            ->orderBy(sprintf('FIELD(%s, %s)', CollectorPeer::ID, implode(', ', $pks)));
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
    $this->display = $this->getUser()->getAttribute('display', 'grid', 'search');
    $this->type = $type;
    $this->sortBy = $sortBy;

    return sfView::SUCCESS;
  }

}
