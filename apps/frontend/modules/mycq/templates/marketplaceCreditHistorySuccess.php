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

        <table class="table table-credit-history">
          <thead>
          <tr>
            <th>Package</th>
            <th>Credits Purchased</th>
            <th>Purchased On</th>
            <th>Expires On</th>
            <th>Status</th>
          </tr>
          </thead>
          <tbody>
          <tr class="not-paid">
            <td>unlimited</td>
            <td>unlimited</td>
            <td>August 18, 2012</td>
            <td>
              -
            </td>
            <td>
              <span class="red">
                processing<br>payment
              </span>
            </td>
          </tr><tr>
            <td>100 credits</td>
            <td>100</td>
            <td>August 18, 2012</td>
            <td>August 18, 2013</td>
            <td>paid</td>
          </tr>
          <tr class="alert">
            <td>100 credits</td>
            <td>1</td>
            <td>August 18, 2011</td>
            <td><strong>August 18, 2012</strong></td>
            <td>
              expiring<br>soon
            </td>
          </tr>
          <tr class="expired">
            <td>100 credits</td>
            <td>0</td>
            <td>2012-06-17 15:57:11</td>
            <td>2012-06-19 12:57:11</td>
            <td>expired</td>
          </tr>
          </tbody>
        </table>
        <div class="cf spacer-bottom-20">
          <button type="submit" class="btn btn-primary pull-right" value="Buy Credits">
            <i class="icon-plus"></i>
            <span>Buy Credits</span>
          </button>
        </div>

        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Package</th>
              <th>Credits Purchased</th>
              <th>Credits Used</th>
              <th>Purchased On</th>
              <th>Expires On</th>
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
              <td><?= $package_transaction->getCreatedAt('F j, Y'); ?></td>
              <td><?= $package_transaction->getExpiryDate('F j, Y'); ?></td>
              <?php if ('dev' == sfConfig::get('sf_environment')): ?>
              <td><?= $package_transaction->getPaymentStatus(); ?></td>
              <?php endif; ?>
            </tr>
          <?php endforeach; else: ?>
            <tr>
              <td colspan="<?= 'dev' == sfConfig::get('sf_environment') ? 6 : 5 ?>">
                You have not purchased any packages yet.
              </td>
            </tr>
          <?php endif; ?>
          </tbody>
        </table>

      </div> <!-- .tab-content-inner.spacer -->
    </div> <!-- .tab-pane.active -->
  </div> <!-- .tab-content -->
</div> <!-- #mycq-tabs -->

