<?php
  /* @var $form CollectorLoginForm */
?>

<?= form_tag('@login', array('class' => 'form-horizontal')) ?>
  <fieldset>
    <?= $form ?>
    <div class="form-actions">
      <input type="submit" class="btn btn-primary blue-button" value="Login" />
    </div>
  </fieldset>
</form>


<fieldset class="rpxnow-login clearfix">
  <legend><?= __('Third Party Accounts:'); ?></legend>
  <iframe
    src="<?= $rpxnow['application_domain']; ?>openid/embed?token_url=<?= url_for('@rpx_token', true); ?>"
    scrolling="no"
    frameBorder="no"
    style="width:350px; height:217px;"
    width="350"
    height="217">
  </iframe>
</fieldset>
