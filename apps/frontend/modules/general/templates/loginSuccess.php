<?php
  /* @var $form CollectorLoginForm */
?>

<div class="main-login-form clearfix">
  <?= form_tag('@login') ?>
    <?= $form ?>
    <input type="submit" value="Login" />
  </form>
</div>


<fieldset class="rpxnow-login clearfix">
  <legend><?= __('Third Party Accounts:'); ?></legend>
  <iframe
    src="<?= $rpxnow['application_domain']; ?>openid/embed?token_url=<?= url_for('@rpx_token', true); ?>"
    scrolling="no"
    frameBorder="no"
    style="width:350px; height:220px;"
    width="350"
    height="220">
  </iframe>
</fieldset>