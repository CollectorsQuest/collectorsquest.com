<?php
/**
 * @var $form CollectorValidateEmailForm
 */
?>

<div class="guide-splash-container">
  <div class="wrapper-top">
    <div class="row-fluid">
      <?php include_partial('misc/guide_intro'); ?>
      <div class="span5">
        <div class="signup-form-splash">
          <h2 class="Chivo webfont">Didn't receive the email?</h2>
          <p style="margin: 10px 0;">
            We should have sent you an email with the download link for the
            "Essential Guide to Collecting" upon the initial registration for Collectors Quest.
            If for some reason you mistyped your email address, please correct it and click "Resend".
          </p>
          <br/>
          <form action="" id="form-verify-email" class="form-horizontal" method="post">
            <?= $form->renderHiddenFields(); ?>
            <div class="span8 spacer-left-reset">
              <?= $form['email']->render(); ?>
            </div>
            <div class="span4">
              <button type="submit" class="btn btn-primary pull-right">Resend</button>
            </div>
          </form>
          <br/><br/>
        </div>
      </div>
    </div>
  </div>
  <?php include_partial('misc/guide_footer'); ?>
</div>

