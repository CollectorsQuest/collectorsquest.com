<?php

/**
 * community actions.
 *
 * @package    CollectorsQuest
 * @subpackage community
 * @author     Kiril Angov
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class communityActions extends cqActions
{
 /**
  * Executes the index action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    if (!$this->featured_week = FeaturedPeer::getCurrentFeatured(FeaturedPeer::TYPE_FEATURED_WEEK))
    {
      $this->featured_week = FeaturedPeer::getLatestFeatured(FeaturedPeer::TYPE_FEATURED_WEEK);
    }

    $this->setTemplate('spotlight');
    $this->executeSpotlight($request);
  }

  public function executeSpotlight()
  {
    if (!isset($this->featured_week) && method_exists($this->getRoute(), 'getObject'))
    {
      $this->featured_week = $this->getRoute()->getObject();
    }
    else
    {
      $this->featured_week = FeaturedPeer::getCurrentFeatured(FeaturedPeer::TYPE_FEATURED_WEEK);
    }

    // Should not happen, but we need to forward to homepage if there is no featured week
    $this->redirectUnless($this->featured_week, '@homepage');

    /**
     * Get some random 3 collections
     *
     * @var $q CollectorCollectionQuery
     */
    $q = CollectorCollectionQuery::create()
       ->filterByNumItems(4, Criteria::GREATER_EQUAL)
       ->limit(3)
       ->addDescendingOrderByColumn('RAND()');
    $this->collections = $q->find();

    $this->featured_collection = $this->featured_week->getCollections(5);

    if (count($this->featured_collection) > 0)
    {
      $this->featured_collection = $this->featured_collection[rand(0,count($this->featured_collection)-1)];
      $this->featured_collector = $this->featured_collection->getCollector();

      $c = new Criteria();
      $c->addAscendingOrderByColumn(CollectionCollectiblePeer::POSITION);
      $c->setLimit(12);

      $this->featured_collectibles = $this->featured_collection->getCollectibles($c);
    }

    $this->city_tags = CollectorPeer::getCity2Tags(35);

    $this->addBreadcrumb($this->__('Community'), '@community');
    $this->addBreadcrumb($this->__('Spotlight'), '@community_spotlight');

    if ($this->featured_week->getId() == 44)
    {
      $this->addBreadcrumb('HARLEY-DAVIDSON WEEK:<br>Ease on Down, Ease on Down the Road');
    }
    else
    {
      $this->addBreadcrumb($this->featured_week->title);
    }

    $this->prependTitle($this->__('Spotlight'));
    $this->prependTitle($this->featured_week->title);

    return sfView::SUCCESS;
  }

  public function execute15MinutesOfFame()
  {
    $this->addBreadcrumb($this->__('Community'), '@community');
    $this->addBreadcrumb($this->__('Your 15 Minutes of Fame'));

    return sfView::SUCCESS;
  }

	public function executeHelp()
	{
		return sfView::SUCCESS;
	}
}
