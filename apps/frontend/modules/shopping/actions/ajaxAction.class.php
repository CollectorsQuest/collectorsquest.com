<?php

/**
 * @method  cqFrontendUser  getUser()
 */
class ajaxAction extends cqAjaxAction
{

  public function preExecute()
  {
    $this->forward404If(cqGateKeeper::locked('shopping_cart'));
  }

  public function getObject(sfRequest $request)
  {
    return null;
  }

  public function executeShoppingCartCollectibleUpdateCountry(sfWebRequest $request)
  {
    $shopping_cart = $this->getUser()->getShoppingCart();
    $this->forward404Unless($shopping_cart instanceof ShoppingCart);

    $group_key = $request->getParameter('group_key');
    $country_code = $request->getParameter('country_iso3166');
    /** @var $shopping_cart ShoppingCart */
    $shopping_cart = $this->getUser()->getShoppingCart();

    /** @var $shopping_cart_collectibles ShoppingCartCollectible[] */
    $shopping_cart_collectibles = ShoppingOrderPeer::cartToOrders($shopping_cart, true);
    if (count($shopping_cart_collectibles[$group_key]))
    {
      $i = 0;
      foreach ($shopping_cart_collectibles[$group_key] as $shopping_cart_collectible)
      {
        try
        {
          $shopping_cart_collectible
            ->setShippingCountryIso3166($country_code)
            ->save();
          $i ++;
        }
        catch (PropelException $e) { ; }
      }
      if ($i == count($shopping_cart_collectibles[$group_key]))
      {
        return $this->success();
      }
    }

    return $this->error('error', 'error');
  }

  public function executeShoppingCartCollectibleUpdateState(sfWebRequest $request)
  {
    $shopping_cart = $this->getUser()->getShoppingCart();
    $this->forward404Unless($shopping_cart instanceof ShoppingCart);

    $id = $request->getParameter('collectible_id');
    $state = $request->getParameter('state');

    if (
      ($cart_collectible = $shopping_cart->getShoppingCartCollectibleById($id)) &&
      $cart_collectible->setShippingStateRegion($state)
    ) {
      try
      {
        $cart_collectible->save();
        return $this->success();
      }
      catch (PropelException $e) { ; }
    }

    return $this->error('error', 'error');
  }

}
