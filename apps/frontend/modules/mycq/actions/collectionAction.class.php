<?php

class collectionAction extends cqFrontendAction
{
  /**
   * @param  sfWebRequest  $request
   * @return mixed|void
   */
  public function execute($request)
  {
    SmartMenu::setSelected('mycq_menu', 'collections');

    $this->collection = $this->getRoute() instanceof sfPropelRoute ?
      $this->getRoute()->getObject() : null;

    $this->redirectUnless(
      $this->getCollector()->isOwnerOf($this->collection),
      '@mycq_collections'
    );

    if ($request->getParameter('cmd'))
    {
      switch ($request->getParameter('cmd'))
      {
        case 'delete':
          $name = $this->collection->getName();
          $url = '@mycq_collections';
          try
          {
            $this->collection->delete();

            $this->getUser()->setFlash(
              'success', sprintf('Your collection "%s" was deleted!', $name)
            );
          }
          catch (PropelException $e)
          {
            if (stripos($e->getMessage(), 'a foreign key constraint fails'))
            {
              $this->getUser()->setFlash(
                'error', sprintf(
                  'Collection "%s" cannot be deleted.
                   Please, try to archive it instead.', $name)
              );

              $url = $this->generateUrl(
                'mycq_collection_by_section', array(
                  'id' => $this->collection->getId(), 'section' => 'details'
                )
              );
            }
          }

          // Redirect appropriately to avoid refreshes to trigger the same action
          $this->redirect($url);

          break;
      }
    }

    switch ($request->getParameter('section'))
    {
      case 'details':
        return $this->executeCollectionDetails($request);
        break;
      case 'reorder':
        return $this->executeCollectiblesReorder($request);
        break;
      case 'collectibles':
      default:
        return $this->executeCollectionCollectibles($request);
        break;
    }
  }

  private function executeCollectionCollectibles(sfWebRequest $request)
  {
    $collector = $this->getCollector();
    $dropbox = $collector->getCollectionDropbox();
    $this->dropbox_total = $dropbox->countCollectibles();

    $this->total = $this->collection->countCollectionCollectibles();

    return 'Collectibles';
  }

  private function executeCollectionDetails(sfWebRequest $request)
  {
    /** @var $collection CollectorCollection */
    $collection = $this->collection;

    $form = new CollectorCollectionEditForm($collection);

    if ($request->isMethod('post'))
    {
      $taintedValues = $request->getParameter($form->getName());
      $form->bind($taintedValues, $request->getFiles($form->getName()));

      if ($form->isValid())
      {
        $values = $form->getValues();

        $collection->setCollectionCategoryId($values['collection_category_id']);
        $collection->setName($values['name']);
        $collection->setDescription($values['description'], 'html');
        $collection->setTags($values['tags']);

        if ($values['thumbnail'] instanceof sfValidatedFile)
        {
          $collection->setThumbnail($values['thumbnail']);
        }

        try
        {
          $collection->save();

          $this->getUser()->setFlash("success", 'Changes were saved!');
          $this->redirect($this->getController()->genUrl(array(
            'sf_route'   => 'mycq_collection_by_section',
            'id' => $collection->getId(),
            'section' => 'details'
          )));
        }
        catch (PropelException $e)
        {
          $this->getUser()->setFlash(
            'error', 'There was a problem while saving the information you provided!'
          );
        }
      }
      else
      {
        $this->defaults = $taintedValues;
      }
    }

    $collector = $this->getCollector();
    $dropbox = $collector->getCollectionDropbox();
    $this->dropbox_total = $dropbox->countCollectibles();

    $this->total = $collection->countCollectionCollectibles();
    $this->collection = $collection;
    $this->form = $form;

    return 'Details';
  }

  protected function executeCollectiblesReorder(sfWebRequest $request)
  {
    $this->total = $this->collection->countCollectionCollectibles();

    $c = new Criteria();
    $c->addAscendingOrderByColumn(CollectionCollectiblePeer::POSITION);
    $c->addDescendingOrderByColumn(CollectionCollectiblePeer::CREATED_AT);

    $this->collectibles = $this->collection->getCollectibles($c);

    return 'CollectiblesReorder';
  }
}
