<?php

class shoppingComponents extends sfComponents
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
        'confirm' => 'This will permanently remove all items from your Shopping Cart. Do you want to continue?'
      )
    );

    return sfView::SUCCESS;
  }

  public function executeShoppingCartCollectible()
  {
    /** @var $shopping_cart_collectible ShoppingCartCollectible */
    if (!$shopping_cart_collectible = $this->getVar('shopping_cart_collectible'))
    {
      return sfView::NONE;
    }

    $this->form = new ShoppingCartCollectibleCheckoutForm(array(
      'shopping_cart_id' => $shopping_cart_collectible->getShoppingCartId(),
      'collectible_for_sale_id' => $shopping_cart_collectible->getCollectibleForSaleId()
    ));

    return sfView::SUCCESS;
  }
}
