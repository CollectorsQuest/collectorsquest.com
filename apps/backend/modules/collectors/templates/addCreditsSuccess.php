<?php
  /* @var $form                 CollectorAddCreditsForm */
  /* @var $collector            Collector */
  /* @var $package_transactions PackageTransaction[] */
  /* @var $package_transaction  PackageTransaction */
?>

<?php if ($sf_user->hasFlash('success')): ?>
<div class="alert">
  <button type="button" class="close" data-dismiss="alert">×</button>
  <?= $sf_user->getFlash('success'); ?>
</div>
<?php endif; ?>

<h3>Transaction History for <em><?= $collector ?></em>:</h3>
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
    <?php if ($package_transactions->count()): foreach ($package_transactions as $package_transaction): ?>
    <tr class=" processing">
      <td><?= $package_transaction->getPackage()->getPackageName(); ?></td>
      <td><?= $package_transaction->getCredits(); ?> (<?= $package_transaction->getCreditsUsed(); ?> used)</td>
      <td><?= $package_transaction->getCreatedAt('F j, Y'); ?></td>
      <td><?= $package_transaction->getExpiryDate('F j, Y'); ?></td>
      <td><?= $package_transaction->getPaymentStatus(); ?></td>
    </tr>
    <?php endforeach; else: ?>
    <tr>
      <td colspan="5">The user has no transaction history</td>
    </tr>
    <?php endif; ?>
  </tbody>
</table>

<hr />
<h3>Add credits:</h3>
<form class="form-horizontal" action="<?= $sf_request->getUri() ?>" method="post">
  <?= $form ?>
  <div class="form-actions">
    <button type="submit" class="btn btn-primary">Save changes</button>
    <?= link_to('Cancel', '@collector', array('class' => 'btn')) ?>
  </div>
</form>

