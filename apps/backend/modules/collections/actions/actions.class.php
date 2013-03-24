<?php

require_once dirname(__FILE__).'/../lib/collectionsGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/collectionsGeneratorHelper.class.php';

/**
 * collections actions.
 *
 * @package    CollectorsQuest
 * @subpackage collections
 * @author     Kiril Angov
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class collectionsActions extends autoCollectionsActions
{

  /**
   * Action CollectionCategory
   *
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeCollectionCategory(sfWebRequest $request)
  {
    $collectionCategories = CollectionCategoryPeer::retrieveForSelect($request->getParameter('q'), $request->getParameter('limit'));

    return $this->renderText(json_encode($collectionCategories));
  }

  protected function buildQuery()
  {
    return parent::buildQuery();
  }

  public function executeListEncourageSeller()
  {
    /* @var $collection CollectorCollection */
    $collection = $this->getRoute()->getObject();
    /* @var $collector Collector */
    $collector = $collection->getCollector();

    $cqEmail = new cqEmail($this->getMailer());
    $cqEmail->send('Collector/become_seller', array(
        'to' => $collector->getEmail(),
        'params' => array(
            'oCollector' => $collector,
        ),
    ));
    $this->getUser()->setFlash('notice', sprintf(
      'A "Become Seller" email was sent to %s (%s).',
      $collector->getDisplayName(),
      $collector->getEmail()
    ));

    return $this->redirect('@collection');
  }

}
