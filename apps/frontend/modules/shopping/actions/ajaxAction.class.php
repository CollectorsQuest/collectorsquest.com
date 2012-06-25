<?php

/**
 * @method  cqFrontendUser  getUser()
 */
class ajaxAction extends cqAjaxAction
{

  public function preExecute()
  {
    $this->forward404If(IceGateKeeper::locked('shopping_cart'));
  }

  public function getObject(sfRequest $request)
  {
    return null;
  }

  public function executeShoppingCartCollectibleUpdateCountry(sfWebRequest $request)
  {
    $shopping_cart = $this->getUser()->getShoppingCart();
    $this->forward404Unless(!$shopping_cart instanceof ShoppingCart);

    $id = $request->getParameter('collectible_id');
    $country_code = $request->getParameter('country_iso3166');

    if (( $cart_collectible = $shopping_cart->getShoppingCartCollectibleById($id) ))
    {
      if ($cart_collectible->updateShippingFromCountryCode($country_code))
      {
        $cart_collectible->save();

        return $this->success();
      }
      else
      {
        return $this->error('bla', 'ga');
      }
    }
    else
    {
      return $this->error('bla', 'ga');
    }
  }

}
