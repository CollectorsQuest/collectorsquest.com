<?php
  /** @var $collector       Collector */
  /** @var $collector_form  CollectorEditForm */
  /** @var $email_form      CollectorEmailChangeForm */

  // set input-xxlarge as the default class of widgets
  foreach($collector_form->getWidgetSchema()->getFields() as $form_field)
  {
    $form_field->setAttribute('class',
      $form_field->getAttribute('class') . ' input-xxlarge');
  }

  // set input-xxlarge as the default class of widgets
  foreach($email_form->getWidgetSchema()->getFields() as $form_field)
  {
    $form_field->setAttribute('class',
      $form_field->getAttribute('class') . ' input-xxlarge');
  }
?>

<div id="mycq-tabs">
  <ul class="nav nav-tabs">
    <li>
      <a href="<?= url_for('@mycq_profile'); ?>">Personal Information</a>
    </li>
    <li class="active">
      <a href="<?= url_for('@mycq_profile_account_info') ?>">Account Information</a>
    </li>
    <li>
      <a href="<?= url_for('@mycq_profile_addresses'); ?>">Mailing Addresses</a>
    </li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active">
      <div class="tab-content-inner spacer">

        <?php cq_sidebar_title('Change Your Email Address'); ?>
        <form action="<?= url_for('@mycq_profile_account_info'); ?>"
              class="form-horizontal" method="post">
          <?= $email_form->renderHiddenFields(); ?>
          <?= $email_form->renderAllErrors(); ?>

          <fieldset class="form-container-center">
            <div class="control-group">
              <label for="textarea" class="control-label">Current Email:</label>
              <div class="controls spacer-top-5">
                <span class="brown">
                  <?= $collector->getEmail(); ?>
                </span>
              </div>
            </div>
            <?= $email_form['password']->renderRow(); ?>
            <?= $email_form['email']->renderRow(); ?>
            <?= $email_form['email_again']->renderRow(); ?>
          </fieldset>

          <fieldset class="form-container-center">
            <div class="form-actions">
              <button type="submit" class="btn btn-primary blue-button">Change Email</button>
              <button type="submit" class="btn gray-button spacer-left">Cancel</button>
              <div class="spacer-left-25">
                <p class="brown spacer-top spacer-left-35">
                  Your email address will not change until you verify it via email
                </p>
              </div>
            </div>
          </fieldset>
        </form> <!-- CollectorEmailChangeForm -->

        <?php cq_sidebar_title('Change Your Account Password'); ?>
        <form action="<?= url_for('@mycq_profile_account_info') ?>"
              method="post" class="form-horizontal">
          <?= $collector_form->renderHiddenFields(); ?>
          <?= $collector_form->renderAllErrors(); ?>

          <fieldset class="form-container-center">
            <div class="control-group">
              <label for="textarea" class="control-label">Username</label>
              <div class="controls spacer-top-5">
                <span class="brown"><?= $collector->getUsername(); ?></span>
              </div>
            </div>
            <?= $collector_form['old_password']->renderRow(); ?>
            <?= $collector_form['password']->renderRow(); ?>
            <?= $collector_form['password_again']->renderRow(); ?>
          </fieldset>

          <fieldset class="form-container-center">
            <div class="form-actions">
              <button type="submit" class="btn btn-primary blue-button">Change Password</button>
              <button type="reset" class="btn gray-button spacer-left">Cancel</button>
            </div>
          </fieldset>
        </form> <!-- CollectorEditForm -->

      </div><!-- .tab-content-inner -->
    </div> <!-- .tab-pane.active -->
    <div class="tab-pane" id="tab4">
      <div class="tab-content-inner spacer">
        <?php
        $link = link_to(
          'View public profile &raquo;', 'collector/me/index',
          array('class' => 'text-v-middle link-align')
        );
        cq_sidebar_title('Edit Your Profile', $link, array('left' => 8, 'right' => 4));
        ?>
        <p>Settings Content</p>
      </div><!-- .tab-content-inner -->
    </div><!-- #tab4.tab-pane -->
  </div><!-- .tab-content -->
</div>
