<?php

  $extra_menu_items = array();

  if ($sf_user->getCollector()->getIsSeller())
  {
    $extra_menu_items['seller_settings'] = array(
        'name' => 'Seller Settings',
        'uri'  => '@mycq_profile_seller_settings',
    );
  }
?>

<ul class="nav nav-tabs">
  <?= SmartMenu::generate('profile_tabs_navzigation', $extra_menu_items); ?>
</ul>