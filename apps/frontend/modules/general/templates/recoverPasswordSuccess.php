<br />
<div class="password-recovery-form" style="width: 400px">

  <?= form_tag('@recover_password', array('class' => 'form-horizontal')); ?>
    <?= $form->renderHiddenFields() ?>

    <?= $form['email']->renderError(); ?>
    <?= $form['email']->renderLabel(); ?>
    <?= $form['email']; ?>
    <div class="clearfix" >
      <?= $form['captcha']->renderError(); ?>
      <?= $form['captcha']->renderLabel(null, array('class' => 'control-label')); ?>
      <?= $form['captcha']; ?>
    </div>

    <input class="btn btn-primary" type="submit" value="Submit" />

  </form>
</div>