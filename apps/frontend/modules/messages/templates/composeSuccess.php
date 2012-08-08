<?php
  /* @var $form ComposePrivateMessageForm */ $form;

  cq_sidebar_title(
          'Compose Message', null,
  array('left' => 8, 'right' => 4, 'class'=>'mycq-red-title row-fluid messages-row indent-bottom15')
  );

  SmartMenu::setSelected('mycq_messages_sidebar', 'compose');
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
