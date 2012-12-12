<?php
  /* @var $package_transactions PackageTransaction[] */
  /* @var $package_transaction  PackageTransaction   */

  SmartMenu::setSelected('mycq_marketplace_tabs', 'promo_codes');
?>

<div id="mycq-tabs">

  <ul class="nav nav-tabs">
    <?= SmartMenu::generate('mycq_marketplace_tabs'); ?>
  </ul>

  <div class="tab-content">
    <div class="tab-pane active">
      <div class="tab-content-inner spacer">



        <table>
          <tbody>
          <?php if (!$pager->isEmpty()): ?>
            <?php foreach ($pager->getResults() as $seller_promotion): ?>
            <tr>
              <td>
                <?= $seller_promotion ?>
              </td>
              <td>
                <?= $seller_promotion->getPromotionCode() ?>
              </td>
              <td>
                <?= $seller_promotion->getAmountType() ?>
              </td>
              <td>
                <?= $seller_promotion->getAmount() ?>
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

