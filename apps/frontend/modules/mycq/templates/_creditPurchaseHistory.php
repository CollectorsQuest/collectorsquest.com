<?php
 /**
  * @var $has_credits           boolean
  * @var $class                 string
  * @var $package_transactions  PackageTransaction[]
  */
?>

<?php if(!$has_credits): ?>
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
  <!-- are we going to use those??
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
  //-->
</div><!-- /.sidebar-title -->

<table class="table table-credit-history">
  <thead>
  <tr>
    <th>Package</th>
    <th>Credits Purchased</th>
    <th>Purchased On</th>
    <th>Expires On</th>
    <?php if ('dev' == sfConfig::get('sf_environment')): ?>
    <th>Status</th>
    <?php endif; ?>
  </tr>
  </thead>
  <tbody>
  <?php if (count($package_transactions)): foreach ($package_transactions as $package_transaction): ?>
    <?php
    // @todo what is the proper way to determine status?
    switch ($package_transaction->getPaymentStatus())
    {
      case PackageTransactionPeer::PAYMENT_STATUS_PAID:
        $class = '';
        if ($package_transaction->getCredits() - $package_transaction->getCreditsUsed() <= 5)
          $class = 'alert';
        if ($package_transaction->getExpiryDate('YmdHis') < date('YmdHis'))
          $class = 'expired';
        break;
      case PackageTransactionPeer::PAYMENT_STATUS_PROCESSING:
        $class = 'processing';
        break;
      default:
        // what are the other cases here?
        break;
    }
    ?>
  <tr class=" <?= $class ?>">
    <td><?= $package_transaction->getPackage()->getPackageName(); ?></td>
    <td><?= $package_transaction->getCredits(); ?></td>
    <td><?= $package_transaction->getCreatedAt('F j, Y'); ?></td>
    <td><?= $package_transaction->getExpiryDate('F j, Y'); ?></td>
    <?php if ('dev' == sfConfig::get('sf_environment')): ?>
    <td>
      <?php
      switch ($class) {
        case '':
          echo 'paid';
          break;
        case 'processing':
          echo '<span class="red">processing<br>payment</span>';
          break;
        case 'alert':
          echo 'expiring<br>soon';
          break;
        case 'expired':
          echo 'expired';
          break;
      }
      ?>
    </td>
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
