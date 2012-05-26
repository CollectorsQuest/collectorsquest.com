<?php
  /** @var $form PasswordRecoveryForm */ $form;
?>

<form class="form-horizontal">
</form>
<div class="password-recovery-form">

  <?= form_tag('@recover_password', array('class' => 'form-horizontal')); ?>
    <fieldset>
      <h1 class="text-center spacer-inner-bottom-30">Forgot your username and/or password?</h1>
        <?= $form->renderUsing('Bootstrap'); ?>
    </fieldset>
    <div class="form-actions">
      <input class="btn btn-primary blue-button" type="submit" value="Recover your account!" />
    </div>
  </form>
</div>
