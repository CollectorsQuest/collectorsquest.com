
<form action="<?= url_for('@shopping_cart'); ?>" method="post">
  <?= $form->renderHiddenFields(); ?>
  <p class="green-text bug-price"><?= money_format('%.2n', (float) $offerPrice); ?></p>
  <?php
    if (!$sf_user->isAuthenticated() || $sf_user->getCollector()->getId() !== $collectible_for_sale->getCollector()->getId())
    {
      echo '<input type="submit" class="btn" value="'. __('Add to Cart') .'"/>';
    }
  ?>
</form>

<div class="well" style="margin-top: 80px;">Sidebar</div>
