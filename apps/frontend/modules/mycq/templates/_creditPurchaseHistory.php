<?php
 /**
  * @var $class                 string
  * @var $package_transactions  PackageTransaction[]
  * @var $seller                Seller
  */
?>

<?php if (!$seller->getCreditsLeft()): ?>
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
          $class = '';
          if ($package_transaction->getCredits() - $package_transaction->getCreditsUsed() <= 5)
          {
            $class = 'alert';
          }
          if (strtotime('-5 days', $package_transaction->getExpiryDate('U')) < time())
          {
            $class = 'alert';
          }
          if ($package_transaction->getExpiryDate('YmdHis') < date('YmdHis'))
          {
            $class = 'expired';
          }
          break;
        case PackageTransactionPeer::PAYMENT_STATUS_PROCESSING :
          $class = 'processing';
          break;
      }
    ?>
  <tr class=" <?= $class ?>">
    <td><?= $package_transaction->getPackage()->getPackageName(); ?></td>
    <td><?= $package_transaction->getCredits() < 9999
      ? $package_transaction->getCredits()
      : 'unlimited'; ?></td>
    <td><?= $package_transaction->getCreditsRemaining(); ?></td>
    <td><?= $package_transaction->getCreatedAt('F j, Y'); ?></td>
    <td><?= $package_transaction->getExpiryDate('F j, Y'); ?></td>
    <td>
      <?php
      switch ($class) {
        case '' :
          echo 'paid';
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
