<div class="password-recovery-form">

  <?= form_tag('@recover_password', array('class' => 'form-horizontal')); ?>
    <fieldset>
      <h1 class="text-center spacer-inner-bottom-30">Forgot your username and/or password?</h1>
 <?php /*
        <?= $form->renderHiddenFields() ?>
        <div class="control-group">
          <?= $form['email']->renderLabel(null, array('class' => 'control-label')); ?>
          <div class="controls">
            <?= $form['email']->renderError(null, array('class' => 'help-inline')); ?>
            <?= $form['email']; ?>
          </div>
        </div>
 */?>
      <div class="control-group">
        <label class="control-label" for="input01">Email: <span class="red-bold">*</span></label>
        <div class="controls">
          <input type="text" class="input-large" id="input01">
          <p class="help-block">Enter your email address.</p>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="input02">Captcha: <span class="red-bold">*</span></label>
        <div class="controls">
          <a href="#" class="inline"><?= (image_tag('frontend/dev/captcha.png')); ?></a>
          <span class="arrow-l-r"></span>
          <input type="text" class="input-large inline input-captcha" maxlength="5" id="input02">
        </div>
      </div>



    </fieldset>
    <div class="form-actions">
      <input class="btn btn-primary blue-button" type="submit" value="Recover your account!" />
    </div>
  </form>
</div>
