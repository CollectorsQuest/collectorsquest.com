<?php

class ajaxAction extends cqAjaxAction
{
  public function getObject(sfRequest $request)
  {
    return null;
  }

  public function executeCollectibleForSale(sfWebRequest $request, $template = null)
  {
    /** @var $collectible_for_Sale CollectibleForSale */
    $collectible_for_sale = FrontendCollectibleForSaleQuery::create()
      ->findOneByCollectibleId($request->getParameter('id'));

    // Show 404 if there is no such collectible for sale
    $this->forward404Unless($collectible_for_sale instanceof CollectibleForsale);

    /* @var $collectible Collectible */
    $collectible = $collectible_for_sale->getCollectible();

    if (!$request->isXmlHttpRequest() || !$collectible_for_sale->isForSale())
    {
      return $this->redirect('collectible_by_slug', array('sf_subject' => $collectible));
    }

    /** @var $collection Collection */
    $collection = $collectible->getCollection();

    /** @var $collector Collector */
    $collector = $collectible->getCollector();

    // Stop right here if we are missing any of these
    $this->forward404Unless($collectible && $collection && $collector);

    // We do not want to show Collectibles which are not assigned to a CollectorCollection
    $this->forward404Unless($collection->getId());

    /**
     * Increment the number of views
     */
    if (!$this->getUser()->getCollector()->isOwnerOf($collectible))
    {
      $collectible->setNumViews($collectible->getNumViews() + 1);
      $collectible->save();
    }

    $this->aetn_show = null;
    $aetn_shows = sfConfig::get('app_aetn_shows');

    foreach ($aetn_shows as $id => $show)
    {
      if ($collection->getId() === $show['collection'])
      {
        $this->aetn_show = $show;
        $this->aetn_show['id'] = $id;

        break;
      }
    }

    $this->collector = $collector;
    $this->collection = $collection;
    $this->collectible = $collectible;
    $this->collectible_for_sale = $collectible_for_sale;
    $this->form = new CollectibleForSaleBuyForm($collectible_for_sale);

    return $template;
  }
}
