<?php
  /* @var $collector Collector */
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

        <?= form_tag('@mycq_marketplace_settings', array('class' => 'form-horizontal', 'multipart' => true)); ?>
        <?= $form->renderHiddenFields(); ?>

        <?php if ($form->hasGlobalErrors()): ?>
          <?= $form->renderAllErrors(); ?>
        <?php else: ?>
          <div class="alert alert-block">
            <h2 class="alert-heading">You must complete all information on this page before you can begin selling.</h2>
            <ul>
              <li>Collectors Quest uses PayPal<sup>®</sup> to process all payments made to sellers on our site.</li>
              <li>
                If you don't have a PayPal<sup>®</sup> account, make sure to
                <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_registration-run" target="_blank">
                  <strong>sign up now!</strong>
                </a>
              </li>
              <li>
                You need to enter your <strong>full name</strong> and <strong>email address</strong>
                exactly as it appears on your PayPal account so that we can verify your information.
              </li>
            </ul>
          </div>
        <?php endif; ?>

        <?php cq_sidebar_title('PayPal Account'); ?>

        <fieldset class="form-container-center spacer-top-20">
          <?= $form['seller_settings_paypal_email']->renderRow(); ?>
          <?= $form['seller_settings_paypal_fname']->renderRow(); ?>
          <?= $form['seller_settings_paypal_lname']->renderRow(); ?>
          <?php // $form['seller_settings_phone_number']->renderRow(); ?>
        </fieldset>


        <?php cq_sidebar_title('Store Information'); ?>

        <fieldset class="form-container-center spacer-top-20">
          <?= $form['seller_settings_store_name']->renderRow() ?>
          <?= $form['seller_settings_store_header_image']->renderRow(array(), null, 'A image to be used as store header that will be resized to 620x67 pixels') ?>
          <?= $form['seller_settings_store_title']->renderRow() ?>
          <?= $form['seller_settings_refunds']->renderRow() ?>
          <?= $form['seller_settings_shipping']->renderRow() ?>
        </fieldset>

        <div class="form-actions">
          <input type="submit" class="btn btn-primary spacer-right-15"
                 name="save[and_add_new_items]" value="Save & Add Items for Sale" />
          <input type="submit" class="btn" value="Save Changes"
                 name="save[and_stay_where_you_are]" />
        </div>
        <?php echo '</form>'; ?>

      </div>
      <!-- .tab-content-inner.spacer -->
    </div>
    <!-- .tab-pane.active -->
  </div>
  <!-- .tab-content -->
</div>
