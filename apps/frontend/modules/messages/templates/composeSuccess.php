<?php
  /* @var $form ComposePrivateMessageForm */ $form;

  use_javascript('/js/jquery/elastic.js');
?>

<?= form_tag('@messages_compose', array('class' => 'form-horizontal form-private-message-compose')) ?>
  <fieldset>
    <?= $form->renderUsing('Bootstrap', array(
        'receiver'=> array('class' => 'span7'),
        'subject' => array('class' => 'span7'),
        'body'    => array('class' => 'span7', 'rows' => 6),
    )); ?>
    <div class="form-actions">
      <input type="submit" class="btn btn-primary" value="Send" />
    </div>
  </fieldset>
</form>