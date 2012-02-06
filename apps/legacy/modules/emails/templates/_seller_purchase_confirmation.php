<?php include_partial('emails/header'); ?>

<p style="margin-bottom: 10px; font-weight: bold;">
  <?= sprintf(__('Congratulations %s', array(), 'emails'), $seller->getDisplayName()); ?>,
</p>
<p>
  You reached an agreement with <strong><?= $buyer->getDisplayName(); ?></strong> about your item
  <strong>"<?= $collectible_for_sale->getCollectible(); ?>"</strong> at the final price of <?= money_format('%.2n', $collectible_for_sale->getPrice()); ?>.<br/><br/>
  You need to contact <strong><?= $buyer->getDisplayName(); ?></strong> within 72 hours to discuss payment and shipping.
  <?php
    echo link_to(
      __('Email %buyer% here.', array('%buyer%' => $buyer->getDisplayName())),
      '@message_compose?to='. $buyer->getId() .'&subject='. urlencode('RE: '. $collectible_for_sale->getCollectible()),
      array('absolute' => true)
    );
  ?>
</p>

<?php include_partial('emails/footer'); ?>
