<?php

/**
 * @method  cqFrontendUser  getUser()
 */
class ajaxAction extends cqAjaxAction
{
  public function getObject(sfRequest $request)
  {
    return null;
  }

  public function executeMwbaCollectible(sfWebRequest $request, $template = null)
  {
    /** @var $collectible Collectible|CollectionCollectible */
    $collectible = CollectibleQuery::create()
      ->findOneById($request->getParameter('id'));

    // Show 404 if there is no such collectible
    $this->forward404Unless($collectible instanceof Collectible);

    if (!$request->isXmlHttpRequest())
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
    $this->incrementCounter($collectible, 'NumViews');

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

    return $template;
  }

}
