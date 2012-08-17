<?php
  /* @var $form CollectorEditForm */
  foreach ($form->getWidgetSchema()->getFields() as $form_field)
  {
    $form_field->setAttribute(
      'class', $form_field->getAttribute('class') . ' input-xxlarge'
    );
  }

  SmartMenu::setSelected('mycq_marketplace_tabs', 'settings');
?>

<div id="mycq-tabs">

  <ul class="nav nav-tabs">
    <?= SmartMenu::generate('mycq_marketplace_tabs'); ?>
  </ul>

  <div class="tab-content">
    <div class="tab-pane active">
      <div class="tab-content-inner spacer">

        <?php cq_sidebar_title('PayPal Account'); ?>
        <?= form_tag('@mycq_marketplace_settings', array('class' => 'form-horizontal')); ?>
        <?= $form->renderHiddenFields(); ?>

        <?php if ($form->hasGlobalErrors()): ?>
          <?= $form->renderAllErrors(); ?>
        <?php elseif (!$collector->getSellerSettingsPaypalAccountStatus()): ?>
          <p>
            Collectors Quest uses PayPal<sup>®</sup> to process all payments made to sellers on our site.
            If you don't have a PayPal<sup>®</sup> account, make sure to
            <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_registration-run" target="_blank">
              <strong>sign up now</strong>
            </a>!
            <br>
            You need to enter your <strong>full name</strong> and <strong>email address</strong>
            exactly as it appears on your PayPal account so that we can verify your information.
          </p><br>
        <?php endif; ?>

        <?php if ($collector->getSellerSettingsPaypalAccountStatus()): ?>
        <fieldset class="form-container-center">
          <div class="control-group" style=" width: 300px; float: left;">
            <label class=" control-label" for="">Account Status:</label>
            <div class="controls" style="padding-top: 5px;">
              <?= $collector->getSellerSettingsPaypalAccountStatus() ?>
            </div>
          </div>
        </fieldset>
        <?php endif; ?>

        <fieldset class="form-container-center">
          <?= $form['seller_settings_paypal_email']->renderRow(); ?>
          <?= $form['seller_settings_paypal_fname']->renderRow(); ?>
          <?= $form['seller_settings_paypal_lname']->renderRow(); ?>
          <?php // $form['seller_settings_phone_number']->renderRow(); ?>
        </fieldset>


        <?php cq_sidebar_title('Store Information'); ?>

        <fieldset class="form-container-center">
          <?= $form['seller_settings_store_name']->renderRow() ?>
          <?= $form['seller_settings_store_title']->renderRow() ?>
          <?= $form['seller_settings_refunds']->renderRow() ?>
          <?= $form['seller_settings_shipping']->renderRow() ?>
        </fieldset>

        <div class="form-actions">
          <input type="submit" class="btn btn-primary spacer-right-15" value="Save Changes" />
          <?= link_to('Cancel', '@mycq_profile', array('class' => 'btn')); ?>
        </div>
        <?php echo '</form>'; ?>

      </div>
      <!-- .tab-content-inner.spacer -->
    </div>
    <!-- .tab-pane.active -->
  </div>
  <!-- .tab-content -->
</div>
