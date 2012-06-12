<?php
  /** @var $collector       Collector */
  /** @var $email_form      CollectorEmailChangeForm */

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
    <li>
      <a href="#tab2" data-toggle="tab">Account Information</a>
    </li>
    <li class="active">
      <a href="<?= url_for('@mycq_profile_email'); ?>">Mailing Addresses</a>
    </li>
    <li>
      <a href="#tab4" data-toggle="tab">Settings</a>
    </li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active">
      <div class="tab-content-inner spacer">
        <form action="<?= url_for('@mycq_profile_email'); ?>" class="form-horizontal" method="post">
          <?= $email_form->renderHiddenFields(); ?>

          <fieldset class="brown-dashes form-container-center">
            <div class="control-group row">
              <div class="offset4 span8">
                <?= $email_form->renderGlobalErrors(); ?>
              </div>
            </div>
            <div class="control-group">
              <label for="textarea" class="control-label">Current email</label>
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

          <fieldset class="brown-dashes form-container-center">
            <div class="form-actions">
              <button type="submit" class="btn btn-primary blue-button">Change email</button>
              <button type="submit" class="btn gray-button spacer-left">Cancel</button>
              <div class="spacer-left-25">
                <p class="brown spacer-top spacer-left-35">
                  Your email address will not change until you confirm it via email
                </p>
              </div>
            </div>
          </fieldset>
        </form> <!-- CollectorEmailChangeForm -->
      </div><!-- .tab-content-inner -->
    </div>
    <div class="tab-pane" id="tab2">
      <div class="tab-content-inner spacer">
        <?php
        $link = link_to(
          'View public profile &raquo;', 'collector/me/index',
          array('class' => 'text-v-middle link-align')
        );
        cq_sidebar_title('Edit Your Account Information', $link, array('left' => 8, 'right' => 4));
        ?>
        <p>Account Information Content</p>
      </div><!-- .tab-content-inner -->
    </div><!-- #tab2.tab-pane -->
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
