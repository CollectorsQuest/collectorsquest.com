<?php
  /* @var $form CollectorLoginForm */
?>

<?php cq_page_title('Sign in to Collectors Quest'); ?>

<div class="login-form-wrapper">
  <div class="row-fluid">
    <div class="span7">
      <?= form_tag('@login', array('class' => 'form-horizontal spacer-top-35')) ?>
      <?= $form->renderGlobalErrors(); ?>
      <fieldset>
        <?= $form['username']->renderRow(); ?>
        <?= $form['password']->renderRow(); ?>
        <div class="control-group ">
          <label class="control-label">&nbsp;</label>
          <div class="controls">
            <?= $form['remember']->render(array('style' => 'float: left; margin-top: 3px;')); ?>
            <label for="login_remember">&nbsp; Remember me for two weeks</label>
          </div>
        </div>
        <div class="form-actions">
          <input type="submit" class="btn btn-primary pull-left" value="Sign In" />
          <span class="spacer-left-15 text-left"">
          - <?= link_to('Forgot your password?', '@recover_password'); ?> <br/>
          &nbsp;&nbsp;&nbsp; - <?= link_to('Sign up for a new account?', '@misc_guide_to_collecting'); ?>
          </span>
        </div>
      </fieldset>

      <?= $form->renderHiddenFields(); ?>
      <?= '</form>' ?>
    </div>
    <div class="span5">
      <fieldset class="rpxnow-login clearfix" id="rpx-login">
        <iframe
          src="<?= $rpxnow['application_domain']; ?>openid/embed?token_url=<?= url_for('@rpx_token', true); ?>"
          scrolling="no" frameBorder="no"
          style="width:350px; height:217px;"
          width="350" height="217">
        </iframe>
      </fieldset>
    </div>
  </div>
</div>

