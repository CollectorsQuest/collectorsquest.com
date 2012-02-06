<?php
/**
 * @var CollectorLoginForm $form
 * @var array $rpxnow
 */
?>

<div id="ajax-login-tabs" style="border: 0; background: none;">
  <ul>
		<li><a href="#tabs-cq-account"><?= __('Collectors\' Quest'); ?></a></li>
		<li><a href="#tabs-third-party"><?= __('OpenID'); ?></a></li>
    <li style="float: right;"><a href="#tabs-signup"><?= __('Sign Up!'); ?></a></li>
	</ul>
	<div id="tabs-cq-account">
    <form action="<?= url_for('@login'); ?>" method="post" style="margin-top: 20px;">
      <div class="span-2" style="text-align: right;">
        <?= cq_label_for($form, 'username', __('Username:')); ?>
        <div style="color: #ccc; font-style: italic;"><?= __('(required)'); ?></div>
      </div>
      <div class="prepend-1 span-6 last">
        <?= cq_input_tag($form, 'username', array('width' => 220)); ?>
      </div>
      <div class="clearfix append-bottom">&nbsp;</div>

      <div class="span-2" style="text-align: right;">
        <?= cq_label_for($form, 'password', __('Password:')); ?>
        <div style="color: #ccc; font-style: italic;"><?= __('(required)'); ?></div>
      </div>
      <div class="prepend-1 span-6 last">
        <?= cq_input_tag($form, 'password', array('width' => 220)); ?>
      </div>
      <div class="clearfix append-bottom">&nbsp;</div>

      <div class="prepend-3 span-6 last">
        <?= $form['remember']; ?>
        <label for="remember"><?= __('Remember me for two weeks'); ?></label>
      </div>
      <div class="clearfix append-bottom">&nbsp;</div>

      <div class="span-9" style="text-align: right;">
        <?php cq_button_submit(__('Sign in to CollectorsQuest.com'), null, 'float: right;'); ?>
      </div>
      <?= $form['_csrf_token']; ?>
    </form>
	</div>
	<div id="tabs-third-party">
    <iframe src="<?= $rpxnow['application_domain']; ?>/openid/embed?token_url=<?= url_for('@rpx_token', true); ?>" scrolling="no" frameBorder="no" style="width:350px;height:220px;" width="350" height="220"></iframe>
	</div>
	<div id="tabs-signup">
    <a href="<?php echo url_for('@collector_signup'); ?>" style="color: #666666;">
    <div style="float: left;" class="choice_box rounded">
      <?= __('Are you a <br> COLLECTOR?'); ?>
      <br><br>
      <font size="2"><?= __('Click Here!'); ?></font>
    </div>
    </a>
    <a href="<?php echo url_for('@seller_signup'); ?>" style="color: #666666;">
    <div style="float: right;" class="choice_box rounded">
      <?= __('Are you a <br /> Seller?'); ?>
      <br><br>
      <font size="2"><?= __('Click Here!'); ?></font>
    </div>
    </a>

    <style type="text/css">
      .choice_box {
        width: 110px;
        text-align: center;
        font-size: 18px;
        background: #F8FBE5;
        padding: 30px;
        margin-top: 25px;
      }

      .choice_box:hover {
        background: #368AA2;
        color: #fff;
        cursor: pointer;
      }
    </style>
	</div>
</div>
