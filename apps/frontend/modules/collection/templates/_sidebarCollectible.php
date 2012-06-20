<?php
/**
 * @var $form CollectibleForSaleBuyForm
 * @var $collectible Collectible
 * @var $collectible_for_sale CollectibleForSale
 */
?>

<?php
  include_component(
    '_sidebar', 'widgetManageCollectible',
    array('collectible' => $collectible)
  );
?>

<?php
  if (
    IceGateKeeper::open('shopping_cart') &&
    isset($collectible_for_sale) &&
    $collectible_for_sale instanceof CollectibleForSale
  ):
?>

  <form action="<?= url_for('@shopping_cart', true); ?>" method="post">
    <div id="price-container">
      <p class="price">
        <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>

        <span class="gray small">
        <?php if (0 === $ship_amount = $collectible_for_sale->getShippingAmountForCountry($sf_user->getCountryCode())): ?>
          with FREE shipping and handling
        <?php elseif (false !== $ship_amount): ?>
          with $<?= $ship_amount ?> shipping and handling to <?= $sf_user->getCountryName(); ?>
        <?php endif; ?>
        </span>
      </p>
      <button type="submit" class="btn btn-primary blue-button pull-left" value="Add Item to Cart">
        <i class="add-to-card-button"></i>
        <span>Add Item to Cart</span>
      </button>
    </div>

    <?= $form->renderHiddenFields(); ?>
  </form>
<?php elseif (isset($collectible_for_sale) && $collectible_for_sale instanceof CollectibleForSale): ?>

  <div id="price-container">
    <p class="price">
      <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>

      <span class="gray small">
      <?php if (0 === $ship_amount = $collectible_for_sale->getShippingAmountForCountry($sf_user->getCountryCode())): ?>
        with FREE shipping and handling
      <?php elseif (false !== $ship_amount): ?>
        with $<?= $ship_amount ?> shipping and handling to <?= $sf_user->getCountryName(); ?>
      <?php endif; ?>
      </span>
    </p>
    <button type="button" class="btn btn-primary blue-button pull-left" value="Add Item to Cart"
            onclick="$('#form-private-message').find('textarea').focus().click(); return false;">
      <span>Send a Message to the Seller</span>
    </button>
  </div>

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
    '_sidebar', 'widgetCollectionCollectibles',
    array('collectible' => $collectible)
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetCollectiblesForSale',
    array(
      'collectible' => $collectible, 'limit' => 3,
      'fallback' => 'random'
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
    array('collectible' => $collectible, 'fallback' => 'random')
  );
?>
