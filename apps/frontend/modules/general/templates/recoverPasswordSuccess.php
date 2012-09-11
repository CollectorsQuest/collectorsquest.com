<?php
  /** @var $form PasswordRecoveryForm */ $form;
?>

<?php cq_page_title('Forgot your username and/or password?'); ?>

<?= form_tag('@recover_password', array('class' => 'form-horizontal spacer-top-35')); ?>
  <fieldset>
    <?= $form->renderUsing('Bootstrap'); ?>
  </fieldset>
  <div class="form-actions">
    <input class="btn btn-primary" type="submit" value="Recover your information!" />
    <span class="spacer-left-15">
      &nbsp;
    </span>
  </div>
</form>
