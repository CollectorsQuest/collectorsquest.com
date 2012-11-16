<?php

require_once dirname(__FILE__).'/../lib/shoppingOrderGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/shoppingOrderGeneratorHelper.class.php';

/**
 * shoppingOrder actions.
 *
 * @package    CollectorsQuest
 * @subpackage shoppingOrder
 * @author     Collectors Quest, Inc.
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class shoppingOrderActions extends autoShoppingOrderActions
{
  public function executeEdit(sfWebRequest $request)
  {
    $this->redirect('shopping_order_view', $this->getRoute()->getObject());
  }

  public function executeView(sfWebRequest $request)
  {
    $this->shopping_order = $this->getRoute()->getObject();
    $this->shopping_payment = $this->shopping_order->getShoppingPayment();
    $this->collectible = $this->shopping_order->getCollectible();


    return sfView::SUCCESS;
  }
}
