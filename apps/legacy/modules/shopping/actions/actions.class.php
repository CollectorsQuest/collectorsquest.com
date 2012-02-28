<?php

class shoppingActions extends cqActions
{
  public function executeCart(sfWebRequest $request)
  {
    $shopping_cart = $this->getUser()->getShoppingCart();

    if ($request->isMethod('post'))
    {
      $form = new CollectibleForSaleBuyForm();
      $form->bind($request->getParameter('collectible_for_sale'));

      if ($form->isValid())
      {
        $values = $form->getValues();

        /** @var $q CollectibleForSaleQuery */
        $q = CollectibleForSaleQuery::create()
           ->filterById($values['id'])
           ->filterByCollectibleId($values['collectible_id']);

        if ($collectible_for_sale = $q->findOne())
        {
          try
          {
            $shopping_cart->addCollectibleForSale($collectible_for_sale);
            $shopping_cart->save();

            $this->getUser()->setFlash('success', $this->__('The collectible was added to your cart.'));
          }
          catch (PropelException $e)
          {
            if (preg_match("/1062 Duplicate entry '(\d+)-(\d+)' for key 'PRIMARY'/i", $e->getMessage()))
            {
              $this->getUser()->setFlash('success', $this->__('This collectible was already in your cart!'));
            }
            else
            {
              throw $e;
            }
          }
        }
        else
        {
          $this->getUser()->setFlash(
            'error', $this->__('We are sorry but there was a problem adding the collectible to your cart!')
          );
        }
      }
      else
      {
        $this->getUser()->setFlash(
          'error', $this->__('We are sorry but there was a problem adding the collectible to your cart!')
        );
      }
    }

    // Make sure we have fresh data
    $shopping_cart->reload();

    if ($shopping_cart->countCollectibleForSales() === 0)
    {
      return 'Empty';
    }

    $this->collectibles_for_sale = $shopping_cart->getCollectibleForSales();

    return sfView::SUCCESS;
  }

}
