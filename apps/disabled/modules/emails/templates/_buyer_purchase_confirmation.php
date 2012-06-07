<?php include_partial('emails/header'); ?>

<p style="margin-bottom: 10px; font-weight: bold;">
  <?= sprintf(__('Congratulations %s', array(), 'emails'), $buyer->getDisplayName()); ?>,
</p>
<p>
  You reached an agreement with <strong><?= $seller->getDisplayName(); ?></strong> about the item for sale
  <strong>"<?= $collectible_for_sale->getCollectible(); ?>"</strong> at the final price of <?= money_format('%.2n', $collectible_for_sale->getPrice()); ?>.
  <strong><?= $seller->getDisplayName(); ?></strong> will contact you within 72 hours to discuss payment and shipping.<br/><br/>
  If you have a question in the meantime, you can
  <?php
    echo link_to(
      __('contact %seller% here.', array('%seller%' => $seller->getDisplayName())),
      '@message_compose?to='. $seller->getId() .'&subject='. urlencode('RE: '. $collectible_for_sale->getCollectible()),
      array('absolute' => true)
    );
  ?>
</p>

<?php include_partial('emails/footer'); ?>
