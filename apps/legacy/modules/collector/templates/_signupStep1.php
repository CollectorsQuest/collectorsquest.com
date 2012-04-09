<?php
  /** @var $form CollectorSignupStep1Form */

  /** @var $rpxnow array */
  $rpxnow = sfConfig::get('app_credentials_rpxnow');

  if('seller' == $sf_user->getAttribute('signup_type', 'collector', 'registration'))
  {
    include_partial(
      'global/wizard_bar',
      array(
        'steps'  => array(1 => __('Choose a package'), __('Account Information'), __('Choose how to pay')),
        'active' => 2
      )
    );
  }
  else
  {
    include_partial(
      'global/wizard_bar',
      array('steps' => array(1 => __('Account Information'), __('Collector Information'), __('Personal Information')), 'active' => 1)
    );
  }
?>

<!--
<div style="padding: 0 0 30px 40px;">
  <fb:login-button registration-url="<?= url_for('@collector_signup_facebook'); ?>"></fb:login-button>
  &nbsp;
  <?= __('We highly recommend that you use your Facebook profile to login / sign up!'); ?>
</div>
//-->

<?php
if ($form->hasGlobalErrors())
{
  slot('flash_error');
    echo $form->renderGlobalErrors();
  end_slot();
}
?>

<div id="collector_signup_1">
  <?php $ssURL = url_for('@collector_signup?step=1'); ?>
  <form action="<?= $ssURL ?>" method="post" id="form-collector-signup-step1">

    <fieldset class="span-16" style="margin-left: 40px;">
      <legend>Account Information</legend>
      <div class="span-3" style="text-align: right;">
        <?= cq_label_for($form, 'username', __('Username:')); ?>
        <div class="required"><?= __('(required)'); ?></div>
      </div>
      <div class="prepend-1 span-7 last">
        <?= cq_input_tag($form, 'username', array('width' => 400)); ?>
        <div class="f-h-w field-help">Only letters, numbers, periods and underscores allowed. Must start with a letter.</div>
        <span style="color:#FF0000"><?= $form['username']->renderError(); ?></span>
      </div>

      <br clear="all"/><br/>
      <div class="span-3" style="text-align: right;">
        <?= cq_label_for($form, 'password', __('Password:')); ?>
        <div class="required"><?= __('(required)'); ?></div>
      </div>
      <div class="prepend-1 span-7 last"> <?= cq_input_tag($form, 'password', array('width' => 250)); ?> <span style="color:#FF0000"><?= $form['password']->renderError(); ?></span> </div>
      <br clear="all"/><br/>
      <div class="span-3" style="text-align: right;">
        <?= cq_label_for($form, 'password_again', __('Confirm Password:')); ?>
        <div class="required"><?= __('(required)'); ?></div>
      </div>
      <div class="prepend-1 span-7 last">
        <?= cq_input_tag($form, 'password_again', array('width' => 250)); ?>
        <span style="color:#FF0000"><?= $form['password_again']->renderError(); ?></span>
      </div>
      <br clear="all"/><br/>
      <div class="span-3" style="text-align: right;">
        <?= cq_label_for($form, 'email', __('E-mail:')); ?>
        <div class="required"><?= __('(required)'); ?></div>
      </div>
      <div class="prepend-1 span-7 last">
        <?= cq_input_tag($form, 'email', array('width' => 400)); ?>
        <span style="color:#FF0000"><?= $form['email']->renderError(); ?></span>
      </div>

      <br clear="all"/><br/>
      <div class="span-3" style="text-align: right;">
        <?= cq_label_for($form, 'display_name', __('Display Name:')); ?>
        <div class="required"><?= __('(required)'); ?></div>
      </div>
      <div class="prepend-1 span-7 last">
        <?= cq_input_tag($form, 'display_name', array('width' => 400)); ?>
        <span style="color:#FF0000"><?= $form['display_name']->renderError(); ?></span>
      </div>
    </fieldset>
    <br clear="all"/>

    <div class="span-13" style="text-align: right;">
      <?php cq_button_submit(__('Next'), 'signup-submit', 'padding-left: 350px;'); ?>
    </div>
    <div class="clearfix append-bottom">&nbsp;</div>

    <?= $form['_csrf_token']; ?>
  </form>
</div>

<?php if('seller' !== $sf_user->getAttribute('signup_type', 'collector', 'registration')): ?>
<div class="clearfix append-bottom">&nbsp;</div>
<div class="prepend-5">
  <a name="openid"></a>
  <iframe src="<?= $rpxnow['application_domain']; ?>/openid/embed?token_url=<?= url_for('@rpx_token', true); ?>"
          scrolling="no" frameBorder="no" style="width:350px; height:215px;" width="350" height="215"></iframe>
</div>
<?php endif; ?>
