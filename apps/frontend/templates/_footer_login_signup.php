<?php
  /**
   * @var $sf_request sfWebRequest
   * @var $sf_user cqFrontendUser
   *
   * @var $signup_form CollectorSignupStep1Form
   * @var $login_form CollectorLoginForm
   */
  use_javascripts_for_form($signup_form);

  $signup_action = isset($signup_action) ? $signup_action : '@collector_signup';
  $login_action = isset($login_action) ? $login_action : '@login';
  $display = $sf_request->getParameter('display', 'signup');

  unset($signup_form['newsletter']);
?>

<div id="footer-form-signup" style="<?= 'signup' == $display ? '' : 'display: none;'; ?>">
  <h2 class="Chivo webfont">Sign Up</h2>
  <?php
  include_partial(
    'global/footer_signup_external_buttons',
    array('action' => 'sign up')
  );
  ?>
  <p class="text-center spacer-15">- OR -</p>

  <form action="<?= url_for($signup_action, true); ?>"
        method="post" class="form-horizontal form-footer">

    <?= $signup_form->renderUsing('BootstrapWithRowFluid'); ?>

    <div class="row-fluid spacer-top-5 spacer-bottom-15">
      <input type="checkbox" name="<?= $signup_form->getName() ?>[newsletter]"
             id="<?= $signup_form->getName() ?>_newsletter" checked="checked">
      Sign me up for the Collectors Quest newsletter
    </div>

    <div class="cf spacer-7 text-center">
      <button type="submit" class="btn btn-primary btn-primary-long">Sign Up</button>
    </div>
  </form>

  <div id="footer-control-login">
    <p class="text-center">
      Already have an account?
      <?= link_to('Sign In', '@login', array('id' => 'footer-control-login-button')); ?>
    </p>
  </div>
</div><!-- #footer-form-signup -->

<div id="footer-form-login" style="<?= 'login' == $display ? '' : 'display: none;' ?>">
  <h2 class="Chivo webfont">Sign In</h2>

  <form action="<?= url_for($login_action, true); ?>"
        method="post" class="form-horizontal form-footer">
    <?php $login_form->renderHiddenFields(); ?>

    <?= $login_form['username']->renderRow(); ?>
    <?= $login_form['password']->renderRow(); ?>

    <div class="row-fluid spacer-7 ">
      <div class="span4 v-center-container-label">
        <span class="v-center">
          <label class=" control-label" for="login_remember">&nbsp;</label>
        </span>
      </div>
      <div class="span8 ">
        <?php
          echo $login_form['remember']->render(
            array('style' => 'float: left; width: 20px; margin-top: 3px;')
          );
        ?>
        <label for="login_remember">Remember me for two weeks</label>
      </div>
    </div>

    <div class="row-fluid spacer-7">
      <div class="span8 spacer-inner-top">
        <?php
          include_partial(
            'global/footer_signup_external_buttons',
            array('action' => 'sign in')
          );
        ?>
      </div>
      <div class="span4">
        <button type="submit" class="btn btn-primary pull-right">Sign&nbsp;In</button>
      </div>
    </div>
    <div class="row-fluid">
      <div class="span12">
        <span class="pull-right">
          <?= link_to('Forgot your password?', '@recover_password'); ?>
        </span>
      </div>
    </div>
  </form>

  <div id="footer-control-signup dn">
    <span class="pull-right">
      Don't have an account yet?
      <?php
        echo link_to(
          'Sign up now!', '@collector_signup',
          array('id' => 'footer-control-signup-button')
        );
      ?>
    </span>
  </div>
</div> <!-- #footer-form-login -->


