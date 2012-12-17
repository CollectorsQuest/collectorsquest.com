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

    $id = $request->getParameter('collectible_id');
    $country_code = $request->getParameter('country_iso3166');

    if (
      ($cart_collectible = $shopping_cart->getShoppingCartCollectibleById($id)) &&
       $cart_collectible->updateShippingFromCountryCode($country_code)
    ) {
      try
      {
        $cart_collectible->setShippingStateRegion(null);
        $cart_collectible->save();
        return $this->success();
      }
      catch (PropelException $e) { ; }
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

  public function executeShoppingCartCollectibleUpdatePromoCode(sfWebRequest $request)
  {
    $shopping_cart = $this->getUser()->getShoppingCart();
    $this->forward404Unless($shopping_cart instanceof ShoppingCart);

    $id = $request->getParameter('collectible_id');
    $code = $request->getParameter('code');


    if ($cart_collectible = $shopping_cart->getShoppingCartCollectibleById($id))
    {
      if (trim($code) == '')
      {
        // Reset code
        $cart_collectible->setSellerPromotion(null);
      }
      else
      {
        /* @var $q SellerPromotionQuery */
        $q = SellerPromotionQuery::create()
          ->filterBySellerId($cart_collectible->getCollectible()->getCollectorId())
          ->filterByIsExpired(false)
          ->add(BaseSellerPromotionPeer::PROMOTION_CODE, $code);

        if ($seller_promotion = $q->findOne() )
        {
          if ($seller_promotion->isValid($this->getUser()->getCollector(), $cart_collectible->getCollectible()))
          {
            $cart_collectible->setSellerPromotion($seller_promotion);
          }
          else
          {
            return $this->output(array('error' => 'We are sorry but this promo code is not valid for this item!'));
          }
        }
        else
        {
          return $this->output(array('error' => 'Sorry, this promo code has expired or is no longer valid!'));
        }
      }

      try
      {
        $cart_collectible->save();

        return $this->success();
      }
      catch (PropelException $e)
      {
        ;
      }
    }

    return $this->error('error', 'error');
  }
}
