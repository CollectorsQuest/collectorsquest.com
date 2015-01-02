<?php
  /**
   * @var $sf_request sfWebRequest
   * @var $sf_user cqFrontendUser
   *
   * @var $signup_form CollectorSignupStep1Form
   * @var $login_form CollectorLoginForm
   */
  use_javascripts_for_form($signup_form);

  $signup_action = isset($signup_action) ? $signup_action : '@misc_guide_to_collecting';
  $login_action = isset($login_action) ? $login_action : '@login';
  $display = $sf_request->getParameter('display', 'signup');
?>

<div id="footer-form-signup" style="<?= 'signup' == $display ? '' : 'display: none;'; ?>">
  <?php
    include_partial(
      'misc/guide_social_login',
      array('action' => 'sign up')
    );
  ?>

  <hr/>
  <div style="background: #FFF5D5; margin: auto; margin-top: -29px; width: 50px; text-align: center; font-size: 150%;">
    OR
  </div>
  <br/>

  <form action="<?= url_for($signup_action, true); ?>" id="form-guide-signup"
        method="post" class="form-horizontal form-footer">

    <?= $signup_form->renderUsing('BootstrapWithRowFluid'); ?>

    <div class="cf spacer-7 text-center">
      <button type="submit" class="btn btn-primary-long">Sign Up</button>
    </div>

    <?= cqStatic::getAyahClient()->getPublisherHTML(); ?>
  </form>

</div><!-- #footer-form-signup -->

<div id="footer-form-login" style="<?= 'login' == $display ? '' : 'display: none;' ?>">
  <?php
    include_partial(
      'misc/guide_social_login',
      array('action' => 'sign in')
    );
  ?>
  <hr/>
  <div style="background: #FFF5D5; margin: auto; margin-top: -29px; width: 50px; text-align: center; font-size: 150%;">
    OR
  </div>
  <br/>

  <form action="<?= url_for($login_action, true); ?>" id="form-guide-login"
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

    <div class="cf spacer-7 text-center">
      <button type="submit" class="btn btn-primary-long">Sign In</button><br/><br/>
      Don't have an account yet?
      <?php
        echo link_to(
          'Sign up now!', '@collector_signup',
          array('id' => 'footer-control-signup-button')
        );
      ?>
    </div>

  </form>
</div> <!-- #footer-form-login -->

<script>
$(document).ready(function()
{
  $('#form-guide-signup, #form-guide-login').submit(function() {
    $('.signup-form-splash').showLoading();
  });
});
</script>
