<?php slot('sidebar'); ?>
  <ul id="sidebar-buttons" class="buttons">
    <?php include_partial('global/li_buttons', array('buttons' => array(0 => array('text' => 'Click here to Sign up Now!', 'route' => '@collector_signup')))); ?>
  </ul>
<?php end_slot(); ?>

<div class="clearfix append-bottom">&nbsp;</div>
<form action="<?= url_for('@login'); ?>" method="post">
  <div class="prepend-1 span-3" style="text-align: right;">
    <?= cq_label_for($form, 'username', __('Username:')); ?>
    <div class="required"><?= __('(required)'); ?></div>
  </div>
  <div class="prepend-1 span-8 last">
    <?= cq_input_tag($form, 'username', array('width' => 420)); ?>
  </div>
  <div class="clearfix append-bottom">&nbsp;</div>

  <div class="prepend-1 span-3" style="text-align: right;">
    <?= cq_label_for($form, 'password', __('Password:')); ?>
    <div class="required"><?= __('(required)'); ?></div>
  </div>
  <div class="prepend-1 span-10 last">
    <div style="padding: 20px 0 0 0; float: right;">
      <?php echo link_to(__('Forgot your password?'), '@login#reminder', array('style' => 'color: grey;')); ?>
    </div>
    <?= cq_input_tag($form, 'password', array('width' => 200, 'align' => 'left')); ?>
  </div>
  <div class="clearfix append-bottom">&nbsp;</div>
  <br>

  <div class="prepend-5 span-6">
    <?= $form['remember']; ?>
    <label for="remember"><?= __('Remember me for two weeks'); ?></label>
  </div>

  <div class="span-5" style="text-align: right;">
    <?php cq_button_submit(__('Sign in to Your Account!'), null, 'float: right;'); ?>
  </div>

  <input type="hidden" name="goto" value="<?= $sf_params->get('goto'); ?>">
  <?= $form['_csrf_token']; ?>
</form>

<div class="clearfix append-bottom">&nbsp;</div><br><br>
<fieldset class="span-10" style="margin-left: 200px;">
  <legend><?= __('Third Party Accounts:'); ?></legend>
  <iframe src="<?= $rpxnow['application_domain']; ?>/openid/embed?token_url=<?= url_for('@rpx_token', true); ?>"
          scrolling="no" frameBorder="no" style="width:350px; height:220px;" width="350" height="220"></iframe>
</fieldset>
<br>

<?php cq_section_title('Forgot your username and/or password?'); ?>

<a name="reminder"></a>
<form action="<?= url_for('@login'); ?>" method="get">
  <div class="prepend-1 span-3" style="text-align: right;">
    <?= cq_label_for($form, 'email', __('Email Address:')); ?>
    <div class="required"><?= __('(required)'); ?></div>
  </div>
  <div class="prepend-1 span-7">
    <?= cq_input_tag($form, 'email', array('width' => 250, 'align' => 'left')); ?>
  </div>

  <div class="prepend-1 span-5 last" style="padding: 5px 0;">
    <?php cq_button_submit(__('Recover Your Account!'), null, 'float: right;'); ?>
  </div>
</form>
<div class="clearfix append-bottom">&nbsp;</div>
