<?php
/* @var $packagesForm SellerPackagesForm */
?>

<div class="row-fluid">
  <div class="span4 signup-text-bg">
    <h3>Why Sell on Collectors Quest?</h3>
    <dl class="signup-text">
      <dt>Heavy Traffic</dt>
      <dd>300,000+ collectors and growing with more monthly traffic than flea markets and floor shows.</dd>
      <dt>Broader Exposure</dt>
      <dd>Have your items for sale matched against related content on the site. See example (link to a page where it matches)</dd>
      <dt>Flat Rate with No Transaction Fees</dt>
      <dd>No fancy math needed. It is what it is. Annual subscribers can sell and replace as many items as you want each month at no additional cost.</dd>
      <dt>Annual Expiration Dates for Credits</dt>
      <dd>Credits for each listing last one year. Once an item is listed, it stays in the marketplace for 6 months.</dd>
    </dl>
  </div>
  <div class="span8">
    <?php if ($packagesForm->hasGlobalErrors()): ?>
    <?= $packagesForm->renderGlobalErrors() ?>
    <?php endif; ?>
    <form action="<?=url_for('seller_packages')?>" method="post" id="form-seller-packages" class="form-horizontal">
      <?= $packagesForm->renderHiddenFields() ?>
      <fieldset>
        <?= $packagesForm['package_id']->renderRow() ?>
        <div class="control-group">
          <?=$packagesForm['promo_code']->renderLabel(null, array('class'=> 'control-label'))?>
          <div class="controls form-inline">
            <?=$packagesForm['promo_code']->render()?>
            <button type="submit" name="applyPromo" id="applyPromo3" value="applyPromo">Apply</button>
            <?=$packagesForm['promo_code']->renderError() ?>
            <?php if (!empty($discountMessage)): ?>
            <span style="color: green; font-weight: bold;"><?=$discountMessage ?></span>
            <?php endif; ?>
          </div>
        </div>
        <?php if (IceGateKeeper::open('mycq_marketplace')): ?>
        <?= $packagesForm['payment_type']->renderRow() ?>
        <?php endif; ?>
      </fieldset>
      <?php if (IceGateKeeper::open('mycq_marketplace')): ?>
      <fieldset id="credit_card" class="form-container-center">
        <?= $packagesForm['cc_type']->renderRow() ?>
        <?= $packagesForm['cc_number']->renderRow() ?>
        <?= $packagesForm['expiry_date']->renderRow(array('class'=> 'span2 inline')) ?>
        <?= $packagesForm['cvv_number']->renderRow() ?>
        <?= $packagesForm['first_name']->renderRow() ?>
        <?= $packagesForm['last_name']->renderRow() ?>
        <?= $packagesForm['street']->renderRow() ?>
        <?= $packagesForm['city']->renderRow() ?>
        <?= $packagesForm['state']->renderRow() ?>
        <?= $packagesForm['zip']->renderRow() ?>
        <?= $packagesForm['country']->renderRow() ?>
      </fieldset>
      <?php endif; ?>
      <fieldset class="form-container-center">
        <label for="<?= $packagesForm['terms']->renderId() ?>" class="radio inline">
          <?= $packagesForm['terms']->render() ?>&nbsp;
          I accept the
          <a href="/blog/terms-and-conditions/" title="terms and conditions" target="_blank">terms and conditions</a> set forth by this site.</label>
      </fieldset>
      <h5 style="margin-top: 10px;">* To avoid interruption of service, annual subscriptions
        automatically renew at the end of the subscription period</h5>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary blue-button">Sign up</button>
      </div>
    </form>
  </div>
</div>
