<?php
  /* @var $form CollectorEditForm */
  foreach ($form->getWidgetSchema()->getFields() as $form_field)
  {
    $form_field->setAttribute(
      'class', $form_field->getAttribute('class') . ' input-xxlarge'
    );
  }

  SmartMenu::setSelected('mycq_marketplace_tabs', 'marketplace_settings');
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
        <?= $form->renderAllErrors(); ?>

        <?php if ($collector->getSellerSettingsPaypalAccountStatus()): ?>
        <fieldset class="form-container-center">
          <div class="control-group ">
            <label class=" control-label" for="">Account Status:</label>
            <div class="controls" style="padding-top: 5px;">
              <?= $collector->getSellerSettingsPaypalAccountStatus() ?>
            </div>
          </div>
          <div class="control-group ">
            <label class=" control-label" for="">Business Name:</label>
            <div class="controls" style="padding-top: 5px;">
              <?= $collector->getSellerSettingsPaypalBusinessName() ?: 'N/A' ?>
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

        <?php cq_sidebar_title('Store Policies'); ?>

        <fieldset class="form-container-center">
          <?= $form['seller_settings_welcome']->renderRow(); ?>
          <?= $form['seller_settings_shipping']->renderRow() ?>
          <?= $form['seller_settings_return_policy']->renderRow(); ?>
          <?= $form['seller_settings_refunds']->renderRow() ?>
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
