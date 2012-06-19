<?php
  /* @var $form CollectorEditForm */
  foreach($form->getWidgetSchema()->getFields() as $form_field)
  {
    $form_field->setAttribute('class',
      $form_field->getAttribute('class') . ' input-xxlarge');
  }

  SmartMenu::setSelected('profile_tabs_navigation', 'seller_settings');
?>

<div id="mycq-tabs">
  <?php include_partial('mycq_tabs_navigation'); ?>
  <div class="tab-content">
    <div class="tab-pane active">
      <div class="tab-content-inner spacer">
        <?php
          $link = link_to(
            'Return to your profile &raquo;', '@mycq_profile',
            array('class' => 'text-v-middle link-align')
          );
          cq_sidebar_title('Edit your store settings', $link, array('left' => 8, 'right' => 4));
        ?>

        <?= form_tag('@mycq_profile_seller_settings', array('class' => 'form-horizontal')); ?>
          <?= $form->renderHiddenFields(); ?>
          <?= $form->renderAllErrors(); ?>

          <fieldset class="brown-dashes form-container-center">
            <?= $form['seller_settings_paypal_email']->renderRow(); ?>
            <?= $form['seller_settings_phone_number']->renderRow(); ?>
          </fieldset>

          <fieldset class="brown-dashes form-container-center">
            <?= $form['seller_settings_store_description']->renderRow(); ?>
            <?= $form['seller_settings_return_policy']->renderRow(); ?>
            <?= $form['seller_settings_payment_accepted']->renderRow(); ?>
          </fieldset>

          <div class="form-actions">
            <input type="submit" class="btn btn-primary blue-button" value="Save Changes" />
            <?= link_to('Cancel', '@mycq_profile', array('class' => 'btn')); ?>
          </div>
        </form>

      </div> <!-- .tab-content-inner.spacer -->
    </div> <!-- .tab-pane.active -->
  </div><!-- .tab-content -->
</div>
