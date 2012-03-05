<?php

class shoppingActions extends cqActions
{
  public function executeCart(sfWebRequest $request)
  {
    $shopping_cart = $this->getUser()->getShoppingCart();
    $this->forward404Unless($shopping_cart instanceof ShoppingCart);

    switch ($request->getParameter('cmd'))
    {
      case 'empty':
        // Delete all the cart Collectibles
        $shopping_cart
          ->getShoppingCartCollectibles()
          ->delete();

        $this->getUser()->setFlash('success', 'Your shopping cart was emptied!', true);
        $this->redirect('@shopping_cart');
        break;

      case 'remove':
        $c = new Criteria();
        $c->add(ShoppingCartCollectiblePeer::COLLECTIBLE_FOR_SALE_ID, $request->getParameter('id'));

        /** @var $cart_collectible ShoppingCartCollectible */
        if ($cart_collectible = $shopping_cart->getShoppingCartCollectibles($c)->getFirst())
        {
          $cart_collectible->delete();

          $this->getUser()->setFlash('success', 'We have removed the item from your cart!', true);
          $this->redirect('@shopping_cart');
        }
        else
        {
          $this->getUser()->setFlash('error', 'We could not remove the item from you shopping cart or it has been already removed!', true);
        }

        break;
    }

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
            $shopping_cart_collectible = new ShoppingCartCollectible();
            $shopping_cart_collectible->setCollectibleForSale($collectible_for_sale);
            $shopping_cart_collectible->setPrice($collectible_for_sale->getPrice());
            $shopping_cart->addShoppingCartCollectible($shopping_cart_collectible);
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
    ShoppingCartPeer::clearRelatedInstancePool();

    $this->addBreadcrumb($this->__('Shopping Cart'), '@shopping_cart');

    if ($shopping_cart->countCollectibleForSales() === 0)
    {
      return 'Empty';
    }

    $this->shopping_cart = $shopping_cart;
    $this->shopping_cart_collectibles = $shopping_cart->getShoppingCartCollectibles();

    return sfView::SUCCESS;
  }

  public function executeCheckout(sfWebRequest $request)
  {
    $shopping_cart = $this->getUser()->getShoppingCart();
    $this->forward404Unless($shopping_cart instanceof ShoppingCart);

    if ($request->isMethod('post'))
    {
      $form = new ShoppingCartCollectibleCheckoutForm();
      $form->bind($request->getParameter('checkout'));

      if ($form->isValid())
      {
        return 'Paypal';
      }
      else
      {
        return sfView::ERROR;
      }
    }

    return $this->redirect('@shopping_cart');
  }

  public function executeCheckoutStandard()
  {

  }

  public function executeCheckoutPaypal()
  {

  }
}
