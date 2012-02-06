<br>
<form action="<?= url_for('@message_compose'); ?>" method="post">
  <div class="span-4" style="text-align: right;">
    <?php echo cq_label_for($form, 'receiver', __('To:')); ?>
    <div style="color: #ccc; font-style: italic;"><?= __('(required)'); ?></div>
  </div>
  <div class="prepend-1 span-13 last">
    <div style="background: #E9E9E9; vertical-align: middle; width: 400px; padding: 5px;">
    <select id="message_receiver" name="message[receiver][]">
      <?php foreach ($receivers as $receiver): ?>
      <option value="<?= $receiver['value']; ?>" class="selected"><?= $receiver['caption']; ?></option>
      <?php endforeach; ?>
    </select>
    </div>
    <?= $form['receiver']->renderError(); ?>
  </div>
  <div class="clear append-bottom">&nbsp;</div>

  <div class="span-4" style="text-align: right;">
    <?= cq_label_for($form, 'subject', __('Subject:')); ?>
    <div style="color: #ccc; font-style: italic;"><?= __('(required)'); ?></div>
  </div>
  <div class="prepend-1 span-13 last">
    <?= cq_input_tag($form, 'subject', array('width' => 400)); ?>
    <?= $form['subject']->renderError(); ?>
  </div>
  <div class="clear append-bottom">&nbsp;</div>

  <div class="span-4" style="text-align: right;">
    <?php echo cq_label_for($form, 'body', __('Message:')); ?>
    <div style="color: #ccc; font-style: italic;"><?= __('(required)'); ?></div>
  </div>
  <div class="prepend-1 span-13 last">
    <?php echo cq_textarea_tag($form, 'body', array('width' => 500, 'height' => 200, 'rich' => false)); ?>
    <?= $form['body']->renderError(); ?>
  </div>
  <div class="clear append-bottom">&nbsp;</div>

  <div class="span-18" style="text-align: right;">
    <?php cq_button_submit(__('Send Message'), null, 'float: right;'); ?>
  </div>

  <?= $form['sender']; ?>
  <?= $form['_csrf_token']; ?>
</form>

<script src="/js/jquery/tags.js" type="text/javascript"></script>

<?php cq_javascript_tag(); ?>
<script type="text/javascript">
$(function()
{
  $('#message_receiver').fcbkcomplete({
    json_url: '<?= url_for('@ajax_autocomplete?section=collectors'); ?>',
    maxitems: 1,
    maxshownitems: 10,
    cache: false,
    filter_case: true,
    filter_hide: true,
    firstselected: true,
    filter_selected: true,
    width: '388px',
    newel: false
  });

  $('#message_body').autogrow();
});
</script>
<?php cq_end_javascript_tag(); ?>
