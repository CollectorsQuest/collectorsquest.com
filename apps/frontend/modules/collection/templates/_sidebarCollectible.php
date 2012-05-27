
<?php if (isset($collectible_for_sale) && $collectible_for_sale instanceof CollectibleForSale): ?>
<form action="<?= url_for('@shopping_cart', true); ?>" method="post">

  <div id="price-container">
    <p class="price">
      <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>
    </p>
    <?php if (!$sf_user->getCollector()->isOwnerOf($collectible_for_sale)): ?>
    <button type="submit" class="btn btn-primary blue-button pull-left" value="Add Item to Cart">
      <i class="add-to-card-button"></i>
      <span>Add Item to Cart</span>
    </button>
    <?php endif; ?>
  </div>

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
    '_sidebar', 'widgetCollectiblesForSale',
    array('collectible' => $collectible, 'limit' => 3)
  );
?>


<?php
  include_component(
    '_sidebar', 'widgetCollections',
    array('collectible' => $collectible)
  );
?>
