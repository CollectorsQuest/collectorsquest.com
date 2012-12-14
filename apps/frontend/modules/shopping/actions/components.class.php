<?php

class shoppingComponents extends cqFrontendComponents
{
  public function executeSidebar()
  {
    $this->buttons = array(
      0 => array(
        'text' => 'Keep Shopping',
        'icon' => 'note',
        'route' => '@marketplace'
      ),
      1 => array(
        'text' => 'Empty Shopping Cart',
        'icon' => 'trash',
        'route' => '@shopping_cart_empty?encrypt=1',
        'confirm' => 'This will permanently remove all items from your Shopping Cart.
                      Do you want to continue?'
      )
    );

    return sfView::SUCCESS;
  }

  public function executeSidebarCheckout()
  {
    $this->shopping_order = ShoppingOrderQuery::create()
      ->findOneByUuid($this->getRequestParameter('uuid'));

    return sfView::SUCCESS;
  }

  public function executeSidebarSignup()
  {
    $shopping_order = ShoppingOrderQuery::create()
      ->findOneByUuid($this->getRequestParameter('uuid'));

    if ($shopping_order && $shopping_order->getCollectorId() !== null) {
      return sfView::NONE;
    }
    else if ($this->getUser()->isAuthenticated()) {
      return sfView::NONE;
    }

    $form = new CollectorSignupSidebarForm();
    $form->getWidget('password')->setAttribute('placeholder', null);
    $form->getWidget('password_again')->setAttribute('placeholder', null);
    $form->setDefaults(array(
      'username' => Utf8::slugify($shopping_order->getShippingFullName(), '_', true),
      'email' => $shopping_order->getBuyerEmail(),
      'seller' => 0
    ));

    $this->form = $form;

    return sfView::SUCCESS;
  }

  public function executeShoppingCartCollectible()
  {
    /** @var $shopping_cart_collectible ShoppingCartCollectible */
    if (!$shopping_cart_collectible = $this->getVar('shopping_cart_collectible'))
    {
      $q = ShoppingCartCollectibleQuery::create()
        ->filterByCollectibleId($this->getRequestParameter('collectible_id'))
        ->filterByShoppingCart($this->getUser()->getShoppingCart())
        ->filterByIsActive(true);

      $shopping_cart_collectible = $q->findOne();
    }

    // We cannot do anything without a ShoppingCart Collectible
    if (!$shopping_cart_collectible)
    {
      return sfView::NONE;
    }

    $this->country = $shopping_cart_collectible->getShippingCountryName();
    $this->cannot_ship =
      ShoppingCartCollectiblePeer::SHIPPING_TYPE_NO_SHIPPING ==
      $shopping_cart_collectible->getShippingType() &&
      $shopping_cart_collectible->getShippingFeeAmount() === null;

    // Get the form
    $this->form = new ShoppingCartCollectibleCheckoutForm($shopping_cart_collectible);
    $this->shopping_cart_collectible = $shopping_cart_collectible;

    return sfView::SUCCESS;
  }

  public function executeShoppingOrder(sfWebRequest $request)
  {

    /** @var $shopping_order ShoppingOrder */
    if (!$shopping_order = $this->getVar('shopping_order') )
    {
      if ($group_key = $request->getParameter('group_key'))
      {
        /**
         * Getting all, then find the right by group_key
         */
        /** @var $shopping_orders  ShoppingOrder[] */
        $shopping_orders = ShoppingOrderPeer::cartToOrders($this->getUser()->getShoppingCart());
        if (isset($shopping_orders[$group_key]))
        {
          $shopping_order = $shopping_orders[$group_key];
        }

      }
    }

    // We cannot do anything without a ShoppingCart Collectible
    if (!$shopping_order)
    {
      return sfView::NONE;
    }

    // Get the form
    $this->form = new ShoppingOrderCheckoutForm($shopping_order);
    $this->shopping_order = $shopping_order;

    return sfView::SUCCESS;
  }


  public function executeSlot1Shipping()
  {
    if ($this->getUser()->isAuthenticated())
    {
      return sfView::NONE;
    }

    $this->form = new CollectorLoginForm();

    return sfView::SUCCESS;
  }

}
