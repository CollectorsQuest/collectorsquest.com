<?php
/**
 * @var $signup_form CollectorGuideSignupForm
 * @var $login_form CollectorGuideLoginForm
 */
?>

<div class="guide-splash-container">
  <div class="wrapper-top">
    <div class="row-fluid">
      <?php include_partial('misc/guide_intro'); ?>
      <div class="span5">
        <div class="signup-form-splash">
        <?php
          include_partial('global/footer_login_signup', array(
            'signup_form' => $signup_form,
            'login_form'  => $login_form,
            'signup_action' => '@misc_guide_to_collecting',
            'login_action' => '@misc_guide_to_collecting',
          ));
        ?>
        </div>
      </div>
    </div>
  </div>
  <?php include_partial('misc/guide_footer'); ?>
</div>
