
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
