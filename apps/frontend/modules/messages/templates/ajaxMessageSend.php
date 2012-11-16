<?php
/**
 * @var $form ComposePrivateMessageForm
 * @var $item String
 */
?>

<h1>Ask a question</h1>

<?php unset($form['copy_for_sender']); ?>
<?= form_tag('@ajax_messages?section=message&page=send', array('class' => 'form-horizontal form-private-message-compose')) ?>
<fieldset>
  <?= $form->renderUsing('Bootstrap', array(
  'receiver'=> array('class' => 'span7'),
  'subject' => array('class' => 'span7'),
  'body'    => array('class' => 'span7', 'rows' => 6),
)); ?>

  <input type="hidden" name="item" value="<?= $item; ?>">

  <div class="control-group ">
    <label for="message_copy_for_sender" class=" control-label">&nbsp;</label>
    <div class="controls">
      <label for="message_copy_for_sender">
        <input type="checkbox" name="<?= $form->getName() ?>[copy_for_sender]"
               id="<?= $form->getName() ?>_copy_for_sender">
        Email me a copy of this message to my email address
      </label>
    </div>
  </div>
  <div class="form-actions">
    <input type="submit" class="btn btn-primary" value="Send" />
  </div>
</fieldset>
<?= '</form>'; ?>
