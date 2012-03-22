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

<?php foreach (PackagePeer::getAllPackagesForSelectGroupedByPlanType() as $group=> $packages): ?>
<div class="prepend-1 span-17 last">
  <fieldset>
    <legend><?php echo $group ?></legend>
    <ul class="unstyled">
      <?php foreach ($packages as $id=> $package): ?>
      <li><?php echo link_to($package, '@collector_signup?step=4&package=' . $id) ?></li>
      <?php endforeach; ?>
    </ul>
  </fieldset>
</div>
<?php endforeach; ?>
