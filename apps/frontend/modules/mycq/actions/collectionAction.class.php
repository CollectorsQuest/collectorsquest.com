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
          $url = $this->generateUrl('mycq_collections');

          $form = new ConfirmDestructiveActionForm();
          $form->getWidget('input')
            ->setLabel('TYPE "DELETE" TO CONFIRM THE DELETION OF YOUR COLLECTION');
          $this->setTemplate('ajaxCollection');
          if ($request->isMethod(sfRequest::POST))
          {
            $form->bind($request->getParameter('confirm'), array());
            if ($form->isValid())
            {

              $name = $this->collection->getName();

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

              return $this->renderPartial('global/loading', array(
                'url' => $url,
              ));
            }
          }
          $this->form = $form;

          return 'Delete';
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

  private function executeCollectionCollectibles()
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

    $form->useFields(array(
      'content_category_plain',
      'name',
      'description',
      'tags'
    ));

    if ($request->isMethod('post'))
    {
      $taintedValues = $request->getParameter($form->getName());
      $form->bind($taintedValues, $request->getFiles($form->getName()));

      if ($form->isValid())
      {
        $values = $form->getValues();

        $collection->setName($values['name']);
        $collection->setDescription($values['description'], 'html');
        $collection->setTags($values['tags']);

        if (isset($values['thumbnail']))
        {
          $collection->setThumbnail($values['thumbnail']);
        }

        try
        {
          $collection->save();

          $this->getUser()->setFlash('success', 'Changes to your collection details were saved!');

          // Did the Collector use the "Save and Add Items" button?
          if ($request->getParameter('save_and_go'))
          {
            return $this->redirect($this->getController()->genUrl(array(
              'sf_route'   => 'mycq_collection_by_section',
              'id' => $collection->getId(),
              'section' => 'collectibles',
            )));
          }
          else
          {
            return $this->redirect($this->getController()->genUrl(array(
              'sf_route'   => 'mycq_collection_by_section',
              'id' => $collection->getId(),
              'section' => 'details',
            )));
          }
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

  protected function executeCollectiblesReorder()
  {
    $this->total = $this->collection->countCollectionCollectibles();

    $c = new Criteria();
    $c->addAscendingOrderByColumn(CollectionCollectiblePeer::POSITION);
    $c->addDescendingOrderByColumn(CollectionCollectiblePeer::CREATED_AT);

    $this->collectibles = $this->collection->getCollectibles($c);

    return 'CollectiblesReorder';
  }
}
