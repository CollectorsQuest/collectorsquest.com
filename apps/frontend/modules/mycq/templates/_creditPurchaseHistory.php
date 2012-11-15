 /**
  * @var $class                 string
  * @var $package_transactions  PackageTransaction[]
  * @var $seller                Seller
  */
?>

<?php if(!$seller->hasPackageCredits()): ?>
<div class="alert alert-block alert-notice in">
  <h4 class="alert-heading">Oh snap! You are out of credits for listing items for sale!</h4>
  <p class="spacer-top">
    Change this and that and try again. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.
  </p>
  <br/>
  <a class="btn btn-primary" href="<?php echo url_for('@seller_packages') ?>">Buy Credits</a>
  <button type="button" class="btn" data-dismiss="alert">Ok</button>
</div>
<?php endif; ?>

<!-- Credit purchase history -->
<div class="row-fluid sidebar-title spacer-top">
  <div class="span8">
    <h3 class="Chivo webfont">Credit History</h3>
  </div>
  <?php /*
  are we going to use those??
  <div class="span4 text-right">
    <span class="show-all-text">
      Show: &nbsp;
    </span>
    <div class="control-group pull-right">
      <div class="btn-filter-all btn-group">
        <a id="filter-paid" class="btn btn-mini btn-filter active" href="#">Paid</a>
        <a id="filter-processing" class="btn btn-mini btn-filter" href="#">Processing</a>
        <a id="filter-expiring" class="btn btn-mini btn-filter " href="#">Expiring</a>
        <a id="filter-expired" class="btn btn-mini btn-filter " href="#">Expired</a>
      </div>
    </div>
  </div>
  */ ?>
</div><!-- /.sidebar-title -->

<table class="table table-credit-history">
  <thead>
  <tr>
    <th>Package</th>
    <th>Credits Purchased</th>
    <th>Credits Used</th>
    <th>Purchased On</th>
    <th>Expires On</th>
    <th>Status</th>
  </tr>
  </thead>
  <tbody>
  <?php if (count($package_transactions)) : foreach ($package_transactions as $package_transaction) : ?>
    <?php
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
    <td><?= $package_transaction->getCreditsUsed(); ?></td>
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
    <?php endforeach; else : ?>
  <tr>
    <td colspan="6">
      You have not purchased any packages yet.
    </td>
  </tr>
    <?php endif; ?>
  </tbody>
</table>
