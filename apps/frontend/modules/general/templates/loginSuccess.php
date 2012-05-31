<?php
  /* @var $form CollectorLoginForm */
?>

<?php cq_page_title('Login to Collectors Quest'); ?>

<?= form_tag('@login', array('class' => 'form-horizontal spacer-top-35')) ?>
  <fieldset>
    <?= $form['username']->renderRow(); ?>
    <?= $form['password']->renderRow(); ?>
    <div class="control-group ">
      <label class="control-label">&nbsp;</label>
      <div class="controls">
        <?= $form['remember']->render(array('style' => 'float: left;')); ?>
        <label for="login_remember">&nbsp; Remember me for two weeks</label>
      </div>
    </div>
    <div class="form-actions">
      <input type="submit" class="btn btn-primary blue-button" value="Login" />
      <span class="spacer-left-15">
        <?= link_to('Forgot your password?', '@recover_password'); ?>
      </span>
    </div>
  </fieldset>

  <?= $form->renderHiddenFields(); ?>
<?= '</form>' ?>

<fieldset class="rpxnow-login clearfix" id="rpx-login">
  <iframe
    src="<?= $rpxnow['application_domain']; ?>openid/embed?token_url=<?= url_for('@rpx_token', true); ?>"
    scrolling="no" frameBorder="no"
    style="width:350px; height:217px;"
    width="350" height="217">
  </iframe>
</fieldset>
