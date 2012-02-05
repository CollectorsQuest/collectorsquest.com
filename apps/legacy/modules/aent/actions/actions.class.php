<?php

/**
 * general actions.
 *
 * @package    CollectorsQuest
 * @subpackage general
 * @author     Kiril Angov
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class aentActions extends cqActions
{
 /**
  * Executes index action
  *
  * @param  sfWebRequest  $request A request object
  * @return string
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->redirect('@aent_landing', 302);
  }

  public function executeLanding()
  {
    $pawn_stars = sfConfig::get('app_aent_pawn_stars');
    $american_pickers = sfConfig::get('app_aent_american_pickers');

    $q = CollectibleQuery::create()
       ->filterByCollectorId($pawn_stars['collector'])
       ->filterByCollectionId($pawn_stars['collection'])
       ->orderById(Criteria::ASC);
    $ps_collectibles = $q->find();

    $q = CollectibleQuery::create()
       ->filterByCollectorId($american_pickers['collector'])
       ->filterByCollectionId($american_pickers['collection'])
       ->orderById(Criteria::ASC);
    $ap_collectibles = $q->find();

    $collectible = array();
    $collectible[1] = $ps_collectibles[3];
    $collectible[2] = $ps_collectibles[1];
    $collectible[3] = $ps_collectibles[2];

    $collectible[4] = $ap_collectibles[1];
    $collectible[5] = $ap_collectibles[0];
    $collectible[6] = $ap_collectibles[8];

    $collectibles = array();
    $collectibles[1] = array($ps_collectibles[0], $ps_collectibles[6]);
    $collectibles[2] = array($ps_collectibles[7], $ps_collectibles[8]);
    $collectibles[3] = array($ps_collectibles[4], $ps_collectibles[5]);

    $collectibles[4] = array($ap_collectibles[2], $ap_collectibles[3]);
    $collectibles[5] = array($ap_collectibles[5], $ap_collectibles[4]);
    $collectibles[6] = array($ap_collectibles[6], $ap_collectibles[7]);

    $this->featured = CollectibleQuery::create()->filterById(
      array(3964, 11789, 9305, 5342, 13721, 15667, 46313, 8932), Criteria::IN
    )->find();

    $this->featured_texts = array(
      0 => "50's and 60's Pop Culture",
      1 => 'Historic Antiques',
      2 => 'Vintage Star Wars Action Figures',
      3 => 'Vintage Lunchboxes',
      4 => 'Dinosaur Dioramas',
      5 => 'Vintage Camera',
      6 => 'Blaction - Black Action Figures',
      7 => '9/11 Memorabilia'
    );

    $this->collectible  = $collectible;
    $this->collectibles = $collectibles;

    return sfView::SUCCESS;
  }

  public function executeCollectible()
  {
    /** @var $collectible Collectible */
    $collectible = $this->getRoute()->getObject();

    /** @var $collection Collection */
    $collection = $collectible->getCollection();

    $this->collectible = $collectible;

    $this->loadHelpers('cqLinks');

    // Building the breadcrumbs
    $this->addBreadcrumb($this->__('Collections'), '@collections');
    $this->addBreadcrumb($collection->getName(), route_for_collection($collection), array('limit' => 38));
    $this->addBreadcrumb(
      $collectible->getName(), null,
      array(
        'id' => 'collectible_'.$collectible->getId().'_name',
        'class' => ($this->getCollector()->isOwnerOf($collectible)) ? 'editable_h1' : ''
      )
    );

    // Building the title
    $this->prependTitle($collection->getName());
    $this->prependTitle($collectible->getName());

    // Building the meta tags
    $this->getResponse()->addMeta('description', $collectible->getDescription('stripped'));
    $this->getResponse()->addMeta('keywords', $collectible->getTagString());

    // Building the geo.* meta tags
    $this->getResponse()->addGeoMeta($collection->getCollector());

    // Setting the Canonical URL
    $this->loadHelpers(array('cqLinks'));
    $this->getResponse()->setCanonicalUrl(url_for_collectible($collectible, true));

    return sfView::SUCCESS;
  }
}
