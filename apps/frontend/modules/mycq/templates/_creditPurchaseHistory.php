<?php
 /**
  * @var $class                 string
  * @var $package_transactions  PackageTransaction[]
  * @var $seller                Seller
  */
?>

<?php if ($seller && !$seller->getCreditsLeft()): ?>
<div class="alert alert-block alert-notice in">
  <h4 class="alert-heading">
    Oh snap! You are out of credits for listing items for sale!
  </h4>
  <p class="spacer-top">
    If you'd like to list more items, please click the link below to purchase more credits:
  </p>
  <br/>
  <a class="btn btn-primary" href="<?php echo url_for('@seller_packages') ?>">Buy Credits</a>
  <button type="button" class="btn" data-dismiss="alert">Close</button>
</div>
<?php endif; ?>

<table class="table table-credit-history">
  <thead>
  <tr>
    <th>Package</th>
    <th>Credits Purchased</th>
    <th>Credits Remaining</th>
    <th>Purchased On</th>
    <th>Expires On</th>

    <th>Status</th>
  </tr>
  </thead>
  <tbody>
  <?php if (count($package_transactions)): ?>
  <?php foreach ($package_transactions as $package_transaction): ?>
    <?php
      /* @var $package_transaction PackageTransaction */
      switch ($package_transaction->getPaymentStatus())
      {
        case PackageTransactionPeer::PAYMENT_STATUS_PAID :
          $class = 'paid';
          if ($package_transaction->getCreditsRemaining() <= 5)
          {
            $class = 'alert';
          }
          if ($package_transaction->isExpired('5 days'))
          {
            $class = 'alert';
          }
          if ($package_transaction->isExpired() || 0 == $package_transaction->getCreditsRemaining())
          {
            $class = 'expired';
          }
          break;
        default:
          $class = $package_transaction->getPaymentStatus();
      }
    ?>
  <tr class=" <?= $class ?>">
    <td><?= $package_transaction->getPackage()->getPackageName(); ?></td>
    <td><?= PackagePeer::PACKAGE_ID_UNLIMITED == $package_transaction->getPackageId()
      ? 'unlimited'
      : $package_transaction->getCredits(); ?></td>
    <td><?= PackagePeer::PACKAGE_ID_UNLIMITED == $package_transaction->getPackageId()
      ? 'unlimited'
      : $package_transaction->getCreditsRemaining(); ?></td>
    <td><?= $package_transaction->getCreatedAt('F jS, Y'); ?></td>
    <td><?= $package_transaction->getExpiryDate('F jS, Y'); ?></td>
    <td>
      <?php
      switch ($class) {
        case 'pending' :
          echo '<span class="red">payment<br>pending</span>';
          break;
        case 'paid' :
          echo 'paid';
          break;
        case 'cancelled' :
          echo 'payment<br>cancelled';
          break;
        case 'processing' :
          echo '<span class="red">processing<br>payment</span>';
          break;
        case 'alert' :
          echo 'expiring<br>soon';
          break;
        case 'expired' :
          echo 'expired';
          break;
        default:
          echo $class;
      }
      ?>
    </td>
  </tr>
    <?php endforeach; else: ?>
  <tr>
    <td colspan="6">
      You have not purchased any packages yet.
    </td>
  </tr>
    <?php endif; ?>
  </tbody>
</table>

<?= link_to('<i class="icon-plus"></i> Buy Listings', '@seller_packages', array(
    'class' => 'btn btn-large btn-primary pull-right spacer-top-5',
)) ?>
