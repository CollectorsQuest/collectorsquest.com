<?php
  /* @var $sf_user cqFrontendUser */
  use_javascripts_for_form($signup_form);
?>

<div id="footer-form-signup">
  <h2 class="Chivo webfont">Sign Up</h2>

  <form action="<?= url_for('@collector_signup', true); ?>" method="post" class="form-horizontal form-footer">
    <?= $signup_form->renderUsing('BootstrapWithRowFluid'); ?>
    <div class="row-fluid spacer-7">
      <div class="span9 spacer-inner-top">
        <?php include_partial('global/footer_signup_external_buttons'); ?>
      </div>
      <div class="span3">
        <button type="submit" class="btn btn-primary blue-button pull-right">Submit</button>
      </div>
    </div>
  </form>

  <div id="footer-control-login">
    <span class="pull-right">
      Already have an account? <?= link_to('Log In', '@login', array('id' => 'footer-control-login-button')); ?>
    </span>
  </div>
</div><!-- #footer-form-signup -->

<div id="footer-form-login" style="display: none">
  <h2 class="Chivo webfont">Log In</h2>

  <form action="<?= url_for('@login', true); ?>" class="form-horizontal form-footer" method="post">
    <?= $login_form->renderUsing('BootstrapWithRowFluid') ?>
    <div class="row-fluid spacer-7">
      <div class="span8 spacer-inner-top">
        <?php include_partial('global/footer_signup_external_buttons'); ?>
      </div>
      <div class="span4">
        <button type="submit" class="btn btn-primary blue-button pull-right">Log&nbsp;In</button>
      </div>
    </div>
    <div class="row-fluid">
      <div class="span12">
        <span class="pull-right"><?= link_to('Forgot your password?', '@recover_password'); ?></span>
      </div>
    </div>
  </form>

  <div id="footer-control-signup" style="display: none">
    <span class="pull-right">
      Don't have an account yet?
      <?= link_to('Sign up', '@collector_signup', array('id' => 'footer-control-signup-button')); ?>
    </span>
  </div>
</div> <!-- #footer-form-login -->
