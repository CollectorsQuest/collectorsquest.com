<?php

require_once dirname(__FILE__).'/../lib/packageTransactionGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/packageTransactionGeneratorHelper.class.php';

/**
 * packageTransaction actions.
 *
 * @package    collectornew
 * @subpackage packageTransaction
 * @author     Prakash Panchal
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class packageTransactionActions extends autoPackageTransactionActions
{

  /**
   * Action Collector
   *
   * @param sfWebRequest $request
   *
   * @return string
   */
  public function executeCollector(sfWebRequest $request)
  {
    $q = $request->getParameter('q');
    $limit = $request->getParameter('limit', 10);

    $items = CollectorQuery::create()
        ->filterByDisplayName("%$q%")
        ->limit($limit)
        ->find()
        ->toKeyValue('Id', 'DisplayName')
        ;

    return $this->renderText(json_encode($items));
  }

}
