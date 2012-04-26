<div class="span3 thumbnail link">
  <img src="http://placehold.it/131x131" alt="">
  <p><?= link_to_collectible($collectible_for_sale->getCollectible(), 'text', array('class' => 'target', 'truncate' => 20)); ?></p>
  <span><?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?></span>
</div>
