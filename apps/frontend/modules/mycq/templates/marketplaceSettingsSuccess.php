<?php
/* @var $form CollectorEditForm */
foreach ($form->getWidgetSchema()->getFields() as $form_field)
{
  $form_field->setAttribute('class',
      $form_field->getAttribute('class') . ' input-xxlarge');
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

        <?= form_tag('@mycq_marketplace_settings', array('class' => 'form-horizontal')); ?>
        <?= $form->renderHiddenFields(); ?>
        <?= $form->renderAllErrors(); ?>

        <fieldset class="form-container-center">
          <?= $form['seller_settings_paypal_email']->renderRow(); ?>
          <?= $form['seller_settings_phone_number']->renderRow(); ?>
        </fieldset>

        <fieldset class="brown-dashes form-container-center">
          <?= $form['seller_settings_return_policy']->renderRow(); ?>
        </fieldset>

        <fieldset class="brown-dashes form-container-center">
          <?= $form['seller_settings_welcome']->renderRow(); ?>
          <?= $form['seller_settings_shipping']->renderRow() ?>
          <?= $form['seller_settings_refunds']->renderRow() ?>
          <?= $form['seller_settings_additional_policies']->renderRow() ?>
        </fieldset>

        <div class="form-actions">
          <input type="submit" class="btn btn-primary spacer-right-15" value="Save Changes" />
          <?= link_to('Cancel', '@mycq_profile', array('class' => 'btn gray-button')); ?>
        </div>
        <?php echo '</form>'; ?>

      </div>
      <!-- .tab-content-inner.spacer -->
    </div>
    <!-- .tab-pane.active -->
  </div>
  <!-- .tab-content -->
</div>
