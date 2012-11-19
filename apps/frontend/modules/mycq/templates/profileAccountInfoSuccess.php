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

  SmartMenu::setSelected('mycq_profile_tabs', 'account_info');
?>

<div id="mycq-tabs">

  <ul class="nav nav-tabs">
    <?= SmartMenu::generate('mycq_profile_tabs'); ?>
  </ul>

  <div class="tab-content">
    <div class="tab-pane active">
      <div class="tab-content-inner spacer">

        <?php cq_section_title('Change Your Email Address'); ?>
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
              <button type="submit" class="btn btn-primary">Change Email</button>
              <button type="submit" class="btn spacer-left">Cancel</button>
              <div class="spacer-left-25">
                <p class="brown spacer-top spacer-left-35">
                  Your email address will not change until you verify it via email
                </p>
              </div>
            </div>
          </fieldset>
        </form> <!-- CollectorEmailChangeForm -->

        <?php cq_section_title('Change Your Account Password'); ?>
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
              <button type="submit" class="btn btn-primary">Change Password</button>
              <button type="reset" class="btn spacer-left">Cancel</button>
            </div>
          </fieldset>
        </form> <!-- CollectorEditForm -->

        <?php cq_section_title('Delete Account'); ?>

        <div class="alert alert-block alert-error">
          <p>
            Deleting your account also deletes all data associated
            with it like collections, collectibles, items for sale, comments, etc.<br>
            Here is some of your current data on CollectorsQuest.com:
            <ul class="spacer-top">
              <li><strong>Username</strong> <?= $collector->getUsername(); ?></li>
              <li><strong>Email</strong> <?= $collector->getEmail(); ?></li>
              <li><strong>Collections:</strong> <?= $collector->countCollectorCollections(); ?></li>
              <li>
                <strong>Collectibles:</strong>
                <?= number_format($collector->countCollectiblesInCollections()); ?>
              </li>
            </ul>
          </p>
          <br/>
          <p style="spacer-top">
            <?php
              $url = url_for('ajax_mycq', array(
                'section' => 'account',
                'page' => 'delete',
                'encrypt' => '1'
              ));
            ?>
            <a href="<?= $url; ?>" class="btn btn-danger open-dialog" onclick="return false;">
              <i class="icon-trash"></i>
              Delete Account
            </a>
          </p>
        </div>
      </div><!-- .tab-content-inner -->
    </div> <!-- .tab-pane.active -->
  </div><!-- .tab-content -->
</div>
