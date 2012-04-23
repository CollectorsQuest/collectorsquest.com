<?= form_tag('@messages_compose', array('class' => 'form-horizontal private-message-form')) ?>
  <fieldset>
    <?= $form->renderUsing('Bootstrap'); ?>
    <div class="form-actions">
      <input type="submit" class="btn btn-primary" value="Send" />
    </div>
  </fieldset>
</form>