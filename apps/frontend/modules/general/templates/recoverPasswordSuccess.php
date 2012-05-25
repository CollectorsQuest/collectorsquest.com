<div class="password-recovery-form">

  <?= form_tag('@recover_password', array('class' => 'form-horizontal')); ?>
    <fieldset>
      <?= $form->renderHiddenFields() ?>
        <div class="control-group">
          <?= $form['email']->renderLabel(null, array('class' => 'control-label')); ?>
          <div class="controls">
            <?= $form['email']->renderError(null, array('class' => 'help-inline')); ?>
            <?= $form['email']; ?>
          </div>
        </div>
        <div class="control-group">
          <?= $form['captcha']->renderError(); ?>
          <?= $form['captcha']->renderLabel(null, array('class' => 'help-inline')); ?>
          <?= $form['captcha']; ?>
        </div>
      </div>
    </fieldset>
    <div class="form-actions">
      <input class="btn btn-primary blue-button" type="submit" value="Submit" />
    </div>
  </form>
</div>
