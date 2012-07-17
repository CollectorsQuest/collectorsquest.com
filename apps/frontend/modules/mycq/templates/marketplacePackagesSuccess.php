<?php
  /* @var $package_transactions PackageTransaction[] */
  /* @var $package_transaction  PackageTransaction   */

  SmartMenu::setSelected('mycq_marketplace_tabs', 'packages');
?>

<div id="mycq-tabs">

  <ul class="nav nav-tabs">
    <?= SmartMenu::generate('mycq_marketplace_tabs'); ?>
  </ul>

  <div class="tab-content">
    <div class="tab-pane active">
      <div class="tab-content-inner spacer">

        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Package</th>
              <th>Credits Purchased</th>
              <th>Credits Used</th>
              <th>Purchased At</th>
              <th>Expires At</th>
              <?php if ('dev' == sfConfig::get('sf_environment')): ?>
              <th>Payment Status</th>
              <?php endif; ?>
            </tr>
          </thead>
          <tbody>
          <?php if (count($package_transactions)): foreach ($package_transactions as $package_transaction): ?>
            <tr>
              <td><?= $package_transaction->getPackage()->getPackageName(); ?></td>
              <td><?= $package_transaction->getCredits(); ?></td>
              <td><?= $package_transaction->getCreditsUsed(); ?></td>
              <td><?= $package_transaction->getCreatedAt(); ?></td>
              <td><?= $package_transaction->getExpiryDate(); ?></td>
              <?php if ('dev' == sfConfig::get('sf_environment')): ?>
              <td><?= $package_transaction->getPaymentStatus(); ?></td>
              <?php endif; ?>
            </tr>
          <?php endforeach; else: ?>
            <tr>
              <td colspan="6">You have not purchased any packages yet.</td>
            </tr>
          <?php endif; ?>
          </tbody>
        </table>

      </div> <!-- .tab-content-inner.spacer -->
    </div> <!-- .tab-pane.active -->
  </div> <!-- .tab-content -->
</div> <!-- #mycq-tabs -->

