<?php
/* @var $packagesForm SellerPackagesForm */
?>

<?php if ($packagesForm->hasGlobalErrors()): ?>
  <?= $packagesForm->renderGlobalErrors(); ?>
<?php elseif (IceGateKeeper::locked('mycq_seller_pay')): ?>
<div class="alert alert-info">
  The market is currently in private beta testing mode.
  If you have received a promo code for participation, please enter it below.
  If you'd like to be a beta tester, please email
  <?= mail_to('info@collectorsquest.com', 'info@collectorsquest.com'); ?> for more information.
</div>
<?php endif; ?>

<div class="row-fluid">
  <div class="span4 info-box-left-y">
    <h3>Why Sell on Collectors Quest?</h3>
    <dl class="text-container">

      <dt>Broader Exposure</dt>
      <dd>
        Have your sale items paired with related and relevant content across the site.
        Check out any page on the site and you'll see!
      </dd>

      <dt>Flat Rate with No Transaction Fees</dt>
      <dd>
        No fancy math needed! Annual subscribers can sell as many items as
        they'd like at no additional cost.
      </dd>

      <dt>List for Six Months</dt>
      <dd>
        Buy Credits that last for one full year. Once an item is marked for sale,
        it remains in the Market for up to 6 months.
      </dd>

      <dt>Payments Processed by PayPal<sup>®</sup></dt>
      <dd>
        Collectors Quest uses PayPal<sup>®</sup> to process all payments made to sellers on our site.
        If you don't have a PayPal<sup>®</sup> account, make sure to
        <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_registration-run"
           target="_blank">
          sign up now
        </a>!
      </dd>
    </dl>
  </div>
  <div class="span8">
    <form action="<?= url_for('seller_packages')?>" method="post"
          id="form-seller-packages" class="form-horizontal" novalidate="novalidate">
      <?= $packagesForm->renderHiddenFields() ?>
      <?= $packagesForm->renderGlobalErrors() ?>

      <fieldset>
        <?= $packagesForm['package_id']->renderRow() ?>
        <div class="control-group">
          <?= $packagesForm['promo_code']->renderLabel(null, array('class'=> 'control-label')) ?>
          <div class="controls form-inline">
            <?= $packagesForm['promo_code']->render() ?>
            <button type="submit" name="applyPromo" id="applyPromo3"
                    value="applyPromo" formnovalidate="formnovalidate">
              Apply
            </button>
            <?= $packagesForm['promo_code']->renderError() ?>
            <?php if (!empty($discountMessage)): ?>
            <span style="color: green; font-weight: bold;"><?=$discountMessage ?></span>
            <?php endif; ?>
          </div>
        </div>
        <?php if (IceGateKeeper::open('mycq_seller_pay')): ?>
        <?= $packagesForm['payment_type']->renderRow() ?>
        <?php endif; ?>
      </fieldset>

      <?php if (IceGateKeeper::open('mycq_seller_pay')): ?>
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

      <div class="control-group spacer-bottom-reset">
        <label class="control-label control-label">&nbsp;</label>
        <div class="controls form-inline">
          <label for="<?= $packagesForm['terms']->renderId() ?>" class="radio inline">
            <?= $packagesForm['terms']->render() ?>&nbsp;
            <?php
            echo sprintf(
              'I accept the %s set forth by Collectors Quest.',
              urldecode(link_to(
                'terms and conditions', '@blog_page?slug=terms-and-conditions',
                array('target' => '_blank')
              ))
            );
            ?>
          </label>
          <?= $packagesForm['terms']->renderError() ?>
        </div>
        <div class="controls form-inline">
          <label for="<?= $packagesForm['fyi']->renderId() ?>" class="radio inline">
            <?= $packagesForm['fyi']->render(array('style' => 'margin-bottom: 25px; float: left; margin-right: 5px; margin-top: 4px;')) ?>
            I acknowledge that all payments made to me for items sold on
            Collectors Quest are processed through PayPal<sup>®</sup>.
          </label>
          <?= $packagesForm['fyi']->renderError() ?>
        </div>
      </div>

      <?php if (IceGateKeeper::open('mycq_seller_pay')): ?>
      <h5 class="spacer-top">* To avoid interruption of service, annual subscriptions
        automatically renew at the end of the subscription period</h5>
      <?php endif; ?>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Purchase Package</button>
        <a href="<?= url_for('mycq') ?>" class="btn spacer-left">
          Cancel
        </a>
      </div>
    </form>
  </div>
</div>
