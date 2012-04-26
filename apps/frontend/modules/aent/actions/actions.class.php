<?php

class aentActions extends cqFrontendActions
{
  public function executeIndex()
  {
    $this->redirect('@aent_landing', 302);
  }

  public function executeLanding()
  {
    return sfView::SUCCESS;
  }

  public function executeAmericanPickers()
  {
    return sfView::SUCCESS;
  }

  public function executePawnStars()
  {
    $pawn_stars = sfConfig::get('app_aent_pawn_stars');

    $q = CollectibleQuery::create()
      ->distinct()
      ->filterByCollectorId($pawn_stars['collector'])
      ->filterByCollectionId($pawn_stars['collection'])
      ->orderById(Criteria::ASC);
    $this->collectibles = $q->find();

    $collectible_ids = array(
      56600, 56597, 56543, 56088, 56396, 56393,
      56599,  2308, 56600, 56545, 56189, 56676,
      56210, 22181, 56509, 56065, 56063, 56029,
      55530, 56579, 33493, 56573, 15630, 56577,
      56078, 56081, 56528, 56514, 56530, 56080,
      28379, 56403, 56262, 56195, 23662, 56263,
      56684, 56214, 56630, 56077, 56684, 56632,
      56541, 56388, 56186, 56075, 56072, 56074,
      56079, 56082, 56103, 56066, 56371, 56213,
      56684, 56683, 56761, 56661,
    );
    shuffle($collectible_ids);

    /** @var $q CollectibleForSaleQuery */
    $q = CollectibleForSaleQuery::create()
      ->filterByCollectibleId($collectible_ids, Criteria::IN)
      ->joinWith('Collectible')->useQuery('Collectible')->endUse()
      ->limit(8)
      ->addAscendingOrderByColumn('FIELD(collectible_id, '. implode(',', $collectible_ids) .')');
    $this->collectibles_for_sale = $q->find();

    return sfView::SUCCESS;
  }

  public function executeStorageWars()
  {
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
        'id' => 'collectible_' . $collectible->getId() . '_name',
        'class' => ($this->getCollector()->isOwnerOf($collectible)) ? 'editable_h1' : ''
      )
    );

    // Building the title
    $this->prependTitle($collection->getName());
    $this->prependTitle($collectible->getName());

    // Building the meta tags
    $this->getResponse()->addMeta('description', $collectible->getDescription('stripped'));
    $this->getResponse()->addMeta('keywords', $collectible->getTagString());

    // Setting the Canonical URL
    $this->loadHelpers(array('cqLinks'));
    $this->getResponse()->setCanonicalUrl(url_for_collectible($collectible, true));

    return sfView::SUCCESS;
  }
}
