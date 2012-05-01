
<?php if ($collectible->isForSale()): ?>
<form action="<?= url_for('@shopping_cart'); ?>" method="post">

  <?php if (!$sf_user->isAuthenticated() || $sf_user->getCollector()->getId() !== $collectible->getCollector()->getId()): ?>

  <button type="submit" class="btn btn-primary btn-large pull-right" value="Add Item to Cart">
      <i class="icon icon-shopping-cart" style="font-size: 18px; padding-right: 10px; margin-right: 5px; border-right: 1px solid gray;"></i>
      Add Item to Cart
    </button>
  <?php endif; ?>
  <p style="font-size: 24px; font-weight: bold; padding: 10px 5px;">
    <?= money_format('%.2n', (float) $collectible->getCollectibleForSale()->getPrice()); ?>
  </p>

  <?= $form->renderHiddenFields(); ?>
</form>
<?php endif; ?>

<?php
  include_component(
    '_sidebar', 'widgetCollector',
    array(
      'collector' => $collectible->getCollector(),
      'collectible' => $collectible,
      'limit' => 0, 'message' => true
    )
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetTags',
    array('collectible' => $collectible)
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetCollections',
    array('collectible' => $collectible)
  );
?>

<div class="row-fluid sidebar-title">
  <div class="span6">
    <h3 class="Chivo webfont">
      Items for Sale
    </h3>
  </div>
  <div class="span6 text-right">
    <a href="/collectors" class="text-v-middle link-align">
      See all items for sale &raquo;
    </a>
  </div>
</div>
<div id="items-for-sale-sidebar">
  <div class="row-fluid">
    <div class="inner-border">
      <div class="span3">
        <a href="#">
          <img src="http://placehold.it/58x58" alt="">
        </a>
      </div>
      <div class="span9 fix-height-text-block">
        <div class="content-container">
          <a href="#" title="1863 Copper Token">
            1863 Copper Token
          </a>
          <p>
            This copper token was issued in 1863...
          </p>
          <span class="price">
          $15.00
          </span>
        </div>
      </div>
    </div>
  </div>
  <div class="row-fluid">
    <div class="inner-border">
      <div class="span3">
        <a href="#">
          <img src="http://placehold.it/58x58" alt="">
        </a>
      </div>
      <div class="span9 fix-height-text-block">
        <div class="content-container">
          <a href="#" title="1863 Copper Token">
            1863 Copper Token
          </a>
          <p>
            This copper token was issued in 1863...
          </p>
          <span class="price">
          $15.00
          </span>
        </div>
      </div>
    </div>
  </div>
  <div class="row-fluid">
    <div class="inner-border">
      <div class="span3">
        <a href="#">
          <img src="http://placehold.it/58x58" alt="">
        </a>
      </div>
      <div class="span9 fix-height-text-block">
        <div class="content-container">
          <a href="#" title="1863 Copper Token">
            1863 Copper Token
          </a>
          <p>
            This copper token was issued in 1863...
          </p>
          <span class="price">
          $15.00
          </span>
        </div>
      </div>
    </div>
  </div>
</div>
