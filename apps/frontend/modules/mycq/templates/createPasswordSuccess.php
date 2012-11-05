<?php
/** @var $collector       Collector */
/** @var $collector_form  CollectorEditForm */
  // set input-xxlarge as the default class of widgets
  foreach($collector_form->getWidgetSchema()->getFields() as $form_field)
  {
    $form_field->setAttribute('class',
      $form_field->getAttribute('class') . ' input-xxlarge');
  }

  SmartMenu::setSelected('mycq_profile_tabs', 'account_info');
?>
<div id="mycq-tabs">

  <ul class="nav nav-tabs">
    <?= SmartMenu::generate('mycq_profile_tabs'); ?>
  </ul>

  <div class="tab-content">
    <div class="tab-pane active">
      <div class="tab-content-inner spacer">

        <?php cq_sidebar_title('Create your password'); ?>
        <form action="<?= url_for('@mycq_profile_create_password'); ?>"
              class="form-horizontal" method="post">
          <?= $collector_form->renderHiddenFields(); ?>
          <?= $collector_form->renderAllErrors(); ?>

            <fieldset class="form-container-center">
              <?= $collector_form['email']->renderRow() ?>
              <?= $collector_form['username']->renderRow() ?>
              <?= $collector_form['password']->renderRow() ?>
              <?= $collector_form['password_again']->renderRow() ?>
            </fieldset>

            <fieldset class="form-container-center">
              <div class="form-actions">
                <button type="submit" class="btn btn-primary">Change Password</button>
                <button type="reset" class="btn spacer-left">Cancel</button>
              </div>
            </fieldset>
        </form>
      </div><!-- .tab-content-inner -->
    </div> <!-- .tab-pane.active -->
  </div><!-- .tab-content -->
</div>
