<?php

/**
 * collectors actions.
 *
 * @package    CollectorsQuest
 * @subpackage collectors
 * @author     Kiril Angov
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class collectorsActions extends cqActions
{
  /**
   * Executes index action
   *
   * @param  sfWebRequest  $request  A request object
   * @return string
   */
  public function executeIndex(sfWebRequest $request)
  {
    $this->addBreadcrumb($this->__('Community'), '@community');
    $this->addBreadcrumb($this->__('Collectors'), '@collectors');

    $c = CollectorQuery::create();
    $c->innerJoinCollectorProfile();
    $c->filterByFacebookId(null, Criteria::ISNULL); //Don't know why this is here

    // $c = new Criteria();
    // $c->setDistinct();
    // $c->add(CollectorPeer::FACEBOOK_ID, null, Criteria::ISNULL);
    // $c->add(CollectorPeer::USER_TYPE, 'Collector');
    // $c->addJoin(CollectorPeer::ID, CollectorCollectionPeer::COLLECTOR_ID, Criteria::RIGHT_JOIN);
    // $c->add(CollectorCollectionPeer::NUM_ITEMS, 0, Criteria::GREATER_THAN);

    if ($filter = $request->getParameter('filter'))
    {
      $c->filterByUserType($filter == 'sellers' ? 'Seller' : 'Collector', Criteria::EQUAL);

      switch ($filter)
      {
        case "most-popular":
          $this->addBreadcrumb($this->__('Most Popular'));

          $c->useCollectionQuery()
            ->filterByNumItems(10, Criteria::GREATER_EQUAL)
            ->endUse()
            ->orderBy('Score', Criteria::DESC);
          break;
        case "online-now":
          $this->addBreadcrumb($this->__('Online Now'));

          $c->addDescendingOrderByColumn(CollectorPeer::LAST_SEEN_AT);
          $c->add(SessionStoragePeer::SESSION_DATA, '%symfony/user/sfUser/authenticated|b:1%', Criteria::LIKE);
          $c->addJoin(CollectorPeer::SESSION_ID, SessionStoragePeer::SESSION_ID, Criteria::LEFT_JOIN);
          break;
        case "near-you":
          $this->addBreadcrumb($this->__('Near You'));

          $collector_profile = $this->getUser()->getCollector()->getProfile();
          if ($collector_profile && $collector_profile->getCountry() == 'United States')
          {
            $pks = CollectorPeer::retrieveByDistance($collector_profile->getZipPostal(), 50, true);
            $pks = array_diff($pks, array(0 => $this->getUser()->getId()));

            $c->add(CollectorPeer::ID, $pks, Criteria::IN);
            $c->addAscendingOrderByColumn(sprintf('FIELD(%s, %s)', CollectorPeer::ID, implode(', ', $pks)));
          }
          break;
        case "friends":
          $this->addBreadcrumb($this->__('Friends'));

          $friend_uids = $this->getUser()->getFacebookFriends();
          $c->add(CollectorPeer::FACEBOOK_ID, $friend_uids, Criteria::IN);
          break;
        case 'sellers':
          $this->addBreadcrumb($this->__('Sellers'));

          $c->filterByUserType('Seller');
          $c->addDescendingOrderByColumn(CollectorPeer::ID);
          break;
        case "latest":
        default:
          $c->addDescendingOrderByColumn(CollectorPeer::ID);
          break;
      }

      $this->filter = $filter;
    }
    else if ($tag = $request->getParameter('tag'))
    {
      $c->add(iceModelTagPeer::NAME, $tag);
      $c->addJoin(CollectorPeer::ID, iceModelTaggingPeer::TAGGABLE_ID, Criteria::LEFT_JOIN);
      $c->addJoin(iceModelTaggingPeer::TAG_ID, iceModelTagPeer::ID, Criteria::LEFT_JOIN);

      $this->addBreadcrumb(ucwords(strtolower($tag)), '@collectors_by_tag?tag=' . $tag);
    }
    else
    {
      $c->addDescendingOrderByColumn(CollectorPeer::ID);
    }

    $per_page = ($request->getParameter('show') == 'all') ? 999 : sfConfig::get('app_pager_list_collectors_max', 9);
    if (true || $this->getUser()->isAuthenticated())
    {
      $per_page += 1;
    }

    $pager = new sfPropelPager('Collector', $per_page);
    $pager->setCriteria($c);

    $pager->setPage($request->getParameter('page', 1));
    $pager->init();

    $this->pager = $pager;
    $this->display = $this->getUser()->getAttribute('display', 'grid', 'collectors');

    return sfView::SUCCESS;
  }

  public function executeSellers(sfWebRequest $request)
  {
    $request->setParameter('filter', 'sellers');

    $this->forward('collectors', 'index');
  }

}
