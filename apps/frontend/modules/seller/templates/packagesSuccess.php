<?php
/* @var $packagesForm SellerPackagesForm */
?>
<?php cq_page_title('Seller Packages') ?>

<?php if ($packagesForm->hasGlobalErrors()): ?>
<?php echo $packagesForm->renderGlobalErrors() ?>
<?php endif; ?>
<form action="" method="post">
  <?php echo $packagesForm->renderHiddenFields() ?>
  <table>
    <?php echo $packagesForm['package_id']->renderRow() ?>
    <tr>
      <th><?php echo $packagesForm['promo_code']->renderLabel() ?></th>
      <td>
        <?php echo $packagesForm['promo_code']->render() ?>
        <button type="submit" name="submit" value="applyPromo">Apply</button>
        <?php echo $packagesForm['promo_code']->renderError() ?>
        <?php if (!empty($discountMessage)): ?>
        <span style="color: green; font-weight: bold;"><?php echo $discountMessage ?></span>
        <?php endif; ?>
      </td>
    </tr>
    <?php echo $packagesForm['payment_type']->renderRow() ?>
  </table>
  <table id="credit_card">
    <?php echo $packagesForm['cc_type']->renderRow() ?>
    <?php echo $packagesForm['cc_number']->renderRow() ?>
    <?php echo $packagesForm['expiry_date']->renderRow() ?>
    <?php echo $packagesForm['cvv_number']->renderRow() ?>
    <?php echo $packagesForm['first_name']->renderRow() ?>
    <?php echo $packagesForm['last_name']->renderRow() ?>
    <?php echo $packagesForm['street']->renderRow() ?>
    <?php echo $packagesForm['city']->renderRow() ?>
    <?php echo $packagesForm['state']->renderRow() ?>
    <?php echo $packagesForm['zip']->renderRow() ?>
    <?php echo $packagesForm['country']->renderRow() ?>
  </table>
  <?php echo $packagesForm['terms']->render() ?>&nbsp;<label for="<?php echo $packagesForm['terms']->renderId() ?>">I accept the
  <a href="/blog/terms-and-conditions/" title="terms and conditions" target="_blank">terms and conditions</a> set forth by this site.</label>
  <h5 style="margin-top: 10px;">* To avoid interruption of service, annual subscriptions
    automatically renew at the end of the subscription period</h5>
  <input type="submit" value="Sign up" />
</form>
