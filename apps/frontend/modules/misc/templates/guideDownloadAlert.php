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

