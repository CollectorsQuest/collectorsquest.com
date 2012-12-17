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
              <th>Discount Type</th>
              <th>Amount</th>
              <th>Expires</th>
              <th>Times Used</th>
              <th>Item for Sale</th>
              <th>Description</th>
              <th>Actions</th>
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
              <?php /*  <td>
                <?= $seller_promotion->getExpiryDate('d M Y'); ?>
              </td> */ ?>
              <td>
                <?php
                  if ($seller_promotion->getExpiryDate())
                  {
                    echo $seller_promotion->getExpiryDate('d M, Y');
                  }
                  else
                  {
                    echo '-';
                  }
                ?>
              </td>
              <td>
                <?= $seller_promotion->getUsedQuantity(); ?>
              </td>
              <td>
                <?php
                  if ($seller_promotion->getCollectibleId())
                  {
                    echo link_to(
                      $seller_promotion->getCollectible()->getName(),
                      'mycq_collectible_by_slug',
                      $seller_promotion->getCollectible()
                    );

                    if ($seller_promotion->getCollectorId())
                    {
                      echo $seller_promotion->getCollectorRelatedByCollectorId()->getEmail();
                    }
                  }
                  else
                  {
                    echo '-';
                  }
                ?>
              </td>
              <td>
                <?= $seller_promotion->getPromotionDesc() ?: '-'; ?>
              </td>
              <td>
                <?= link_to('Delete',
                '@mycq_marketplace_promo_code_delete?id='. $seller_promotion->getId(),
                array('class' => 'close-button', 'rel' => 'tooltip', 'title' => 'Delete this Promo Code',
                  'confirm' => 'Are you sure you want to delete the Promo Code?'
                )); ?>
              </td>
            </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="8">
                  You have not created any promo codes yet. Please use the <strong>"New Promo Code"</strong>
                  button to create your first promo code.
                </td>
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
        <div class="form-actions">
          <?= link_to('New Promo Code', '@ajax_mycq?section=promoCode&page=Create',
          array('class' => 'btn btn-primary open-dialog', 'onclick' => 'return false;')); ?>
        </div>

      </div> <!-- .tab-content-inner.spacer -->
    </div> <!-- .tab-pane.active -->
  </div> <!-- .tab-content -->
</div> <!-- #mycq-tabs -->

