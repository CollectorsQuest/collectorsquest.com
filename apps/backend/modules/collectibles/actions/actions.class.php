<?php

require_once dirname(__FILE__).'/../lib/collectiblesGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/collectiblesGeneratorHelper.class.php';

/**
 * collectibles actions.
 *
 * @package    CollectorsQuest
 * @subpackage collectibles
 * @author     Kiril Angov
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class collectiblesActions extends autoCollectiblesActions
{
  public function executeListEncourageSeller()
  {
    /* @var $collectible Collectible */
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

    return $this->redirect('collectible');
  }
}
