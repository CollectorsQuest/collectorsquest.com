<?php

/**
 * @method  cqFrontendUser  getUser()
 */
class ajaxAction extends IceAjaxAction
{

  public function preExecute()
  {
    $this->forward404If(IceGateKeeper::locked('shopping_cart'));
  }

  public function getObject(sfWebRequest $request) {
  }

  public function executeShoppingCartCollectibleUpdateCountry(sfWebRequest $request)
  {
    $this->forward404Unless($shopping_cart = $this->getUser()->getShoppingCart());

    $id = $request->getParameter('collectible_id');
    $country_code = $request->getParameter('country_iso3166');

    if (( $cart_collectible = $shopping_cart->getShoppingCartCollectibleById($id) ))
    {
      if ($cart_collectible->updateShippingFromCountryCode($country_code))
      {
        $cart_collectible->save();

        return $this->output(array('success' => true));
      }
      else
      {
        return $this->output(array('success'=> false, 'bla' => 'ga'));
      }
    }
    else
    {
      return $this->output(array('success' => false));
    }
  }

}
