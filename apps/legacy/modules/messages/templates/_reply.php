<hr class="span-18" style="margin-left: 20px;">
<a name="reply"></a>
<form id="messages-reply" action="<?= url_for('@message_compose'); ?>" method="post">
  <input type="hidden" name="message[id]" id="message_id" value="<?= $message->getId(); ?>">

  <div class="span-3" style="text-align: right;">Reply To:</div>
  <div class="prepend-1 span-14 last">
    <?= $message->getCollectorRelatedBySender()->getDisplayName(); ?>
    <input type="hidden" name="message[receiver]" id="message_receiver" value="<?= $message->getSender(); ?>">
  </div>
  <div class="clear">&nbsp;</div>

  <div class="span-3" style="text-align: right;">Subject:</div>
  <div class="prepend-1 span-14 last">
    <span class="ui-icon ui-icon-pencil ui-icon-editable"></span>
    <span class="subject"><?= $message->getReplySubject(); ?></span>
    <input type="hidden" name="message[subject]" id="message_subject" value="<?= $message->getReplySubject(); ?>">
  </div>
  <div class="clear">&nbsp;</div>

  <div class="span-3" style="text-align: right;">Message:</div>
  <div class="prepend-1 span-14 last">
    <div style="background: #E9E9E9; width: 512px; padding: 5px;">
      <?php echo cq_textarea_tag($form, 'body', array('width' => 500, 'height' => 200, 'rich' => false)); ?>
      <?= $form['body']->renderError(); ?>
    </div>
  </div>
  <div class="clear">&nbsp;</div>

  <div class="span-17" style="text-align: right;">
    <?php cq_button_submit(__('Send Reply'), null, 'float: right;'); ?>
  </div>

  <?= $form['sender']; ?>
  <?= $form['_csrf_token']; ?>
</form>

<?php cq_javascript_tag(); ?>
<script type="text/javascript">
$(document).ready(function()
{
  $('#message_body').autogrow();
  $('#messages-reply .subject').editable(
  function(value)
  {
    $('#message_subject').val(value);
    $('#message_body').focus();

    return(value);
  },
  {
    indicator: '<img src="/images/loading.gif"/>',
    tooltip: '<?= __('Click to edit...'); ?>',
    width: 500
  });
});
</script>
<?php cq_end_javascript_tag(); ?>
