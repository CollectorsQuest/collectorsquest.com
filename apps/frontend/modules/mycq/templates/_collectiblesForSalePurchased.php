<?php
/* @var $shopping_order ShoppingOrder */
/* @var $pager sfPropelPager */
/* @var $sf_params sfParameterHolder */
?>

<?php if ($pager->getNbResults() > 0): ?>

  <?php foreach ($pager->getResults() as $i => $shopping_order): ?>
  <div class="span3 collectible_sold_items_grid_view_square link">
    <?php
      echo link_to_if(
        $shopping_order->getShoppingPayment()->getStatus() === ShoppingPaymentPeer::STATUS_COMPLETED,
        image_tag_collectible(
          $shopping_order->getCollectible(), '140x140',
          array('width' => 130, 'height' => 130)
        ),
        '@mycq_shopping_order?uuid=' . $shopping_order->getUuid()
      );
    ?>
    <span class="purchased">
      <?php
        echo $shopping_order->getShoppingPayment()->getStatus() === ShoppingPaymentPeer::STATUS_COMPLETED ?
          'PURCHASED' : 'IN PROGRESS'
      ?>
    </span>
    <p>
      <?php
        echo link_to_if(
          $shopping_order->getShoppingPayment()->getStatus() === ShoppingPaymentPeer::STATUS_COMPLETED,
          cqStatic::truncateText(
            $shopping_order->getCollectible()->getName(), 30, '...', true
          ),
          '@mycq_shopping_order?uuid=' . $shopping_order->getUuid(),
          array('class' => 'target')
        );
      ?>
      <strong class="pull-right spacer-top">
        <?= money_format('%.2n', (float) $shopping_order->getCollectibleForSale()->getPrice()); ?>
      </strong>
    </p>
  </div>
  <?php endforeach; ?>

<?php else: ?>
<div class="mycq-create-collectible">
  <div class="row-fluid spacer-inner-top-20">
    <div class="span4">
      <a href="<?php echo url_for('@marketplace'); ?>"
         class="btn-create-collection-middle">
        <i class="icon-shopping-cart"></i>
      </a>
    </div>
    <div class="span8">
      <div class="btn-large-box">
        <a href="<?php echo url_for('@marketplace'); ?>">
          Start<br/> Shopping
        </a>
      </div>
    </div>
  </div>
</div>
<div class="no-collections-uploaded-box link">
  <?php if ($sf_params->get('q')): ?>
  <span class="Chivo webfont info-no-collections-uploaded spacer-top-15">
        None of your Items for Sale match search term: <strong><?= $sf_params->get('q'); ?></strong>
      </span>
  <?php else: ?>
  <span class="Chivo webfont info-no-collections-uploaded spacer-bottom">
        Buy items from the marketplace today!<br/>
        Shop the Market Now!
      </span>
  <?php endif; ?>
</div>
<?php endif; ?>
