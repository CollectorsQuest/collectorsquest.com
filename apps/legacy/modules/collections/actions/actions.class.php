<?php

/**
 * collections actions.
 *
 * @package    CollectorsQuest
 * @subpackage collections
 * @author     Kiril Angov
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class collectionsActions extends cqActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->addBreadcrumb($this->__('Community'), '@community');
    $this->addBreadcrumb($this->__('Collections'), '@collections');

    $this->prependTitle($this->__('Collections'));

    $c = new Criteria();
    $c->setDistinct();
    $c->add(CollectorCollectionPeer::NUM_ITEMS, 0, Criteria::NOT_EQUAL);
    $c->addJoin(CollectorCollectionPeer::COLLECTOR_ID, CollectorPeer::ID, Criteria::LEFT_JOIN);

    if ($filter = $request->getParameter('filter'))
    {
      switch($filter)
      {
        case 'most-popular':
          $this->addBreadcrumb($this->__('Most Popular'));
          $this->prependTitle($this->__('Most Popular'));

          $c->add(CollectorCollectionPeer::NUM_VIEWS, 100, Criteria::GREATER_EQUAL);
          $c->addDescendingOrderByColumn(CollectorCollectionPeer::NUM_VIEWS);
          break;
        case 'most-talked-about':
          $this->addBreadcrumb($this->__('Most Talked-About'));
          $this->prependTitle($this->__('Most Talked-About'));

          $c->add(CollectorCollectionPeer::NUM_COMMENTS, 0, Criteria::GREATER_THAN);
          $c->addDescendingOrderByColumn(CollectorCollectionPeer::NUM_COMMENTS);
          break;
        case 'most-recent':
        default:
          $this->addBreadcrumb($this->__('Most Recent'));
          $this->prependTitle($this->__('Most Recent'));

          $c->addDescendingOrderByColumn(CollectorCollectionPeer::CREATED_AT);
          break;
      }

      $this->filter = $filter;
    }
    else if ($collector_slug = $request->getParameter('collector'))
    {
      if ($collector = CollectorPeer::retrieveBySlug($collector_slug))
      {
        $c->add(CollectorPeer::SLUG, $collector_slug);
        $c->addDescendingOrderByColumn(CollectorCollectionPeer::CREATED_AT);

        $this->addBreadcrumb(sprintf($this->__('Collections of %s'), $collector->getDisplayName()), '@collections_by_collector='. $collector_slug);
        $this->prependTitle(sprintf($this->__('Collections of %s'), $collector->getDisplayName()));
      }
    }
    else if ($tag = $request->getParameter('tag'))
    {
      $c->add(iceModelTagPeer::NAME, $tag);
      $c->addJoin(CollectorCollectionPeer::ID, iceModelTaggingPeer::TAGGABLE_ID, Criteria::LEFT_JOIN);
      $c->addJoin(iceModelTaggingPeer::TAG_ID, iceModelTagPeer::ID, Criteria::LEFT_JOIN);

      $this->addBreadcrumb(ucwords(strtolower($tag)), '@collections_by_tag='. $tag);
      $this->prependTitle(ucwords(strtolower($tag)));
    }
    else
    {
      $c->addDescendingOrderByColumn(CollectorCollectionPeer::UPDATED_AT);
    }

    $per_page = ($request->getParameter('show') == 'all') ? 999 : sfConfig::get('app_pager_list_collections_max', 14);
    if (true || $this->getUser()->isAuthenticated()) $per_page += 1;

    $pager = new sfPropelPager('Collection', $per_page);
    $pager->setCriteria($c);

    // Added By Prakash Panchal On 31-Mar-2011.
    $snPage = ($this->getRequestParameter('jpage')) ? $this->getRequestParameter('jpage', 1) : $this->getRequestParameter('page', 1);

    $pager->setPage($snPage);
    $pager->init();

    $this->pager = $pager;
    $this->display = $this->getUser()->getAttribute('display', 'grid', 'collections');

    return sfView::SUCCESS;
  }
}
