<?php
$ssAction = @$ssAction ? : '@seller_become?id=';
$bFreeSubscription = @$bFreeSubscription ? : 0;
$packages = PackagePeer::doSelectAllGrouppedByPlanType();

?>
<br clear="all" />
<?php if ($sf_request->getParameter('msg')): ?>
<ul class="error_list">
  <li>
    <?php echo __('Your plan has been expired') ?>
  </li>
</ul>
<?php endif; ?>

<table width="902px" border="0" cellspacing="0" cellpadding="0" class="formoffer">
  <tr>
    <td width="50%" valign="top">
      <table width="90%" border="0" cellspacing="0" cellpadding="0" class="offerleft">
        <tr>
          <td valign="top"><h6>Heavy Traffic</h6>

            <div class="space_text">500,000+ collectors and growing with more monthly traffic than flea markets and floor shows.</div>
            <h6>Broader Exposure</h6>

            <div class="space_text">
              Have your items for sale matched against related content on the site.
              <a href="#" onClick="window.open('/images/example.jpg','','width=762,height=524'); return false;">See example</a>.
            </div>
            <h6>Flat Rate with No Transaction Fees</h6>

            <div class="space_text">No fancy math needed. It is what it is. Annual subscribers can sell and replace as many items as you want each month at no additional cost.</div>
            <h6>Annual Expiration Dates and Payment Choice</h6>

            <div class="space_text">You can continue listing your items for sale for up to one year. Payment method for your customers is YOUR decision.</div>
          </td>
        </tr>
      </table>
      <p>&nbsp;</p>
    </td>
    <td width="50%" valign="top">
      <table border="0" cellspacing="0" cellpadding="0" class="offerright" style="width: 100%;">
        <tr>
          <td>
            <fieldset>
              <legend>Choose a Plan</legend>

              <?php if ($packages): ?>
              <?php foreach ($packages as $group=> $packagesGroup): ?>
                <?php /* @var $packagesGroup Package[] */ ?>
                <h5><?php echo __('%plan% Plan', array('%plan%'=> $group)) ?></h5>
                <ul style="width: 100%;">
                  <?php foreach ($packagesGroup as $package): ?>
                  <li><?php echo link_to($package->getPackageName(), '@seller_become?package_id=' . $package->getId()); ?></li>
                  <?php endforeach; ?>
                </ul>
                <?php endforeach; ?>
              <?php endif; ?>
            </fieldset>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
