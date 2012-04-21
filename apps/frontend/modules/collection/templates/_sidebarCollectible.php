
<?php if ($collectible->isForSale()): ?>
<form action="<?= url_for('@shopping_cart'); ?>" method="post">
  <?= $form->renderHiddenFields(); ?>
  <p class="green-text bug-price"><?= money_format('%.2n', (float) $collectible->getCollectibleForSale()->getPrice()); ?></p>
  <?php
    if (!$sf_user->isAuthenticated() || $sf_user->getCollector()->getId() !== $collectible->getCollector()->getId())
    {
      echo '<input type="submit" class="btn" value="'. __('Add to Cart') .'"/>';
    }
  ?>
</form>
<?php endif; ?>

<?php
  include_component(
    '_sidebar', 'widgetRelatedCollections',
    array('collectible' => $collectible)
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetTags',
    array('collectible' => $collectible, 'title' => 'Tags for this Item')
  );
?>
