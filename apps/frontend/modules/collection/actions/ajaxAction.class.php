<?php

class ajaxAction extends IceAjaxAction
{
  protected function getObject(sfWebRequest $request)
  {
    $object = null;

    if ($request->getParameter('id'))
    {
      $object = CollectionQuery::create()->findOneById($request->getParameter('id'));
    }

    return $object;
  }

  /**
   * @param  sfWebRequest  $request
   * @return mixed
   */
  public function execute($request)
  {
    $this->collection = $this->getObject($request);

    // Stop here if there is no valid Collector or the Collector object is not saved yet
    $this->forward404if(!$this->collection || $this->collection->isNew());

    return parent::execute($request);
  }

  protected function executeCollectiblesCarousel(sfWebRequest $request)
  {
    $data = array();

    $collectible = CollectionCollectibleQuery::create()
      ->filterByCollection($this->collection)
      ->filterByCollectibleId($request->getParameter('collectible_id'))
      ->findOne();

    if ($collectible)
    {
      $collectible_ids = $this->collection->getCollectibleIds();
      $position = array_search($collectible->getId(), $collectible_ids);
    }
    else
    {
      $position = 0;
    }

    $p = (int) $request->getParameter('p', -1) + 1;
    $s = (int) $request->getParameter('s', 4);

    $q = CollectionCollectibleQuery::create()
      ->filterByCollection($this->collection)
      ->offset(4 - $position + ($p * $s))
      ->limit($s)
      ->orderByPosition(Criteria::ASC)
      ->orderByCreatedAt(Criteria::ASC);

    if ($collectibles = $q->find())
    {
      $this->loadHelpers('cqLinks', 'cqImages');

      /** @var $collectible Collectible */
      foreach ($collectibles as $collectible)
      {
        $data[] = array(
          'id' => $collectible->getId(),
          'url' => url_for_collectible($collectible),
          'thumbnails' => array(
            'x75' => src_tag_collectible($collectible, '75x75'),
            'x150' => src_tag_collectible($collectible, '150x150')
          )
        );
      }
    }

    return $this->output(array('collectibles' => $data));
  }
}
