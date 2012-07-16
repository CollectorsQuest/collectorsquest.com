<?php
  /** @var $form PasswordRecoveryForm */ $form;
?>

<?php cq_page_title('Forgot your username and/or password?'); ?>

<?= form_tag('@recover_password', array('class' => 'form-horizontal spacer-top-35')); ?>
  <fieldset>
    <?= $form->renderUsing('Bootstrap'); ?>
  </fieldset>
  <div class="form-actions">
    <input class="btn btn-primary" type="submit" value="Recover your account!" />
    <span class="spacer-left-15">
      <?= link_to('Did you recover your credentials?', '@login'); ?>
    </span>
  </div>
</form>
