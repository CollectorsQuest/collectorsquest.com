<?php
use_helper('Form');

include_partial(
  'global/wizard_bar',
  array(
    'steps'  => array(1 => __('Choose a package'), __('Account Information'), __('Choose how to pay')),
    'active' => 1
  )
);
?>

<div id="sellersignup_2">
  <?php foreach (PackagePeer::getAllPackagesForSelectGroupedByPlanType() as $group=> $packages): ?>
  <h3><?php echo $group ?></h3>
  <ul>
    <?php foreach ($packages as $id=> $package): ?>
    <li><?php echo link_to($package, '@collector_signup?step=4&package=' . $id) ?></li>
    <?php endforeach; ?>
</ul>
  <?php endforeach; ?>
</div>
