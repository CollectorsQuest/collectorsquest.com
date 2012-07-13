<?php
  /* @var $form CollectorEditForm */
  foreach ($form->getWidgetSchema()->getFields() as $form_field)
  {
    $form_field->setAttribute(
      'class', $form_field->getAttribute('class') . ' input-xxlarge'
    );
  }

  SmartMenu::setSelected('mycq_profile_tabs', 'store_settings');
?>

<div id="mycq-tabs">

  <ul class="nav nav-tabs">
    <?= SmartMenu::generate('mycq_profile_tabs'); ?>
  </ul>

  <div class="tab-content">
    <div class="tab-pane active">
      <div class="tab-content-inner spacer">

        <?php cq_sidebar_title('PayPal Account'); ?>
        <?= form_tag('@mycq_profile_store_settings', array('class' => 'form-horizontal')); ?>
        <?= $form->renderHiddenFields(); ?>

        <?php if ($form->hasGlobalErrors()): ?>
          <?= $form->renderAllErrors(); ?>
        <?php elseif (!$collector->getSellerSettingsPaypalAccountStatus()): ?>
          <p>
            You need to enter your <strong>full name</strong> and <strong>email address</strong>
            exactly as it appears on your PayPal account so that we can verify your information.
          </p><br/>
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

        <div class="form-actions">
          <input type="submit" class="btn btn-primary spacer-right-15" value="Save Changes" />
          <?= link_to('Cancel', '@mycq_profile', array('class' => 'btn')); ?>
        </div>

        <?php cq_sidebar_title('Store Policies'); ?>

        <fieldset class="form-container-center">
          <?= $form['seller_settings_refunds']->renderRow() ?>
          <?= $form['seller_settings_return_policy']->renderRow(); ?>
          <?= $form['seller_settings_welcome']->renderRow(); ?>
          <?= $form['seller_settings_shipping']->renderRow() ?>
          <?= $form['seller_settings_additional_policies']->renderRow() ?>
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
