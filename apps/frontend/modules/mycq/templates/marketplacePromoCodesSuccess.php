<?php
/**
 * @var $seller_promotion SellerPromotion
  */

SmartMenu::setSelected('mycq_marketplace_tabs', 'promo_codes');
?>

<div id="mycq-tabs">

  <ul class="nav nav-tabs">
    <?= SmartMenu::generate('mycq_marketplace_tabs'); ?>
  </ul>

  <div class="tab-content">
    <div class="tab-pane active">
      <div class="tab-content-inner spacer">



        <table class="table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Code</th>
              <th>Amount Type</th>
              <th>Amount</th>
              <th>Expire Date</th>
              <th>Time left</th>
              <th>Quantity</th>
              <th>Quantity Left</th>
            </tr>
          </thead>
          <tbody>
          <?php if (!$pager->isEmpty()): ?>
            <?php foreach ($pager->getResults() as $seller_promotion): ?>
            <tr>
              <td>
                <?= $seller_promotion; ?>
              </td>
              <td>
                <?= $seller_promotion->getPromotionCode(); ?>
              </td>
              <td>
                <?= $seller_promotion->getAmountType(); ?>
              </td>
              <td>
                <?php if ($seller_promotion->getAmountType() != SellerPromotionPeer::AMOUNT_TYPE_FREE_SHIPPING): ?>
                <?php
                echo $seller_promotion->getAmountType() == SellerPromotionPeer::AMOUNT_TYPE_FIXED ? '$' : '';
                echo  sprintf('%01.2f', (float) $seller_promotion->getAmount());
                echo $seller_promotion->getAmountType() == SellerPromotionPeer::AMOUNT_TYPE_PERCENTAGE? '%' : '';
                ?>
                <?php else: ?>
                &nbsp;
                <?php endif; ?>
              </td>
              <td>
                <?= $seller_promotion->getExpiryDate('d M Y'); ?>
              </td>
              <td>
                <?php if ($seller_promotion->getExpiryDate(null)): ?>
                  <?= $seller_promotion->getTimeLeft()->invert == 1
                  ? $seller_promotion->getTimeLeft()->format('%d days %H:%I') : 0; ?>
                <?php endif; ?>
              </td>
              <td>
                <?= $seller_promotion->getQuantity() != 0 ?  $seller_promotion->getQuantity() : ''; ?>
              </td>
              <td>
                <?= ($seller_promotion->getQuantity() - $seller_promotion->getUsedQuantity()); ?>
              </td>
            </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td>You have no promo codes.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>

        <div class="row-fluid text-center">
          <?php
          include_component(
            'global', 'pagination',
            array(
              'pager' => $pager,
            )
          );
          ?>
        </div>



        <?= link_to('New Promo Code', '@ajax_mycq?section=promoCode&page=Create',
        array('class' => 'btn btn-primary open-dialog', 'onclick' => 'return false;')); ?>



      </div> <!-- .tab-content-inner.spacer -->
    </div> <!-- .tab-pane.active -->
  </div> <!-- .tab-content -->
</div> <!-- #mycq-tabs -->

