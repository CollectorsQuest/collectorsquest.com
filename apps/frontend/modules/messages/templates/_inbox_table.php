<table id="private-messages-inbox" class="private-messages-list table table-bordered">
  <tbody>
  <?php if (!$pager->isEmpty()): foreach ($pager->getResults() as $message):
    $message_link = url_for('messages_show', $message)
      .($message->getIsRead() ? '' : '#latest-message');
  ?>
    <tr
      class="linkify <?= $message->getIsRead() ? 'read' : 'unread' ?>"
      data-url="<?= $message_link; ?>"
    >
      <td class="select-col dont-linkify">
        <input type="checkbox" name="ids[]" value="<?= $message->getId() ?>" class="<?= $message->getIsRead() ? 'read' : 'unread' ?>" />
      </td>
      <td class="sender-col">
        <?= image_tag_collector($message->getCollectorRelatedBySenderId(),
          '50x50', array('class' => 'avatar')); ?>
        From:&nbsp;<?= link_to_collector($message->getCollectorRelatedBySenderId()); ?><br/>
        <span class="font10" title="<?= $message->getCreatedAt('c') ?>">
          <?= time_ago_in_words($message->getCreatedAt('U')); ?> ago
        </span>
      </td>
      <td class="message-col">
        <?= link_to($message->getSubject(), $message_link); ?>
        <span>
          <?= Utf8::truncateHtmlKeepWordsWhole($message->getBody(), 150); ?>
        </span>
      </td>
    </tr>
  <?php endforeach; elseif ('' == $search): ?>
    <tr>
      <td colspan="5">You have no messages in your inbox.</td>
    </tr>
  <?php else: ?>
    <tr>
      <td colspan="5">No messages matched your search term "<?= $search?>".</td>
    </tr>
  <?php endif; ?>
  </tbody>
</table>

<div class="row-fluid text-center">
  <?php
    include_component(
      'global', 'pagination',
      array(
        'pager' => $pager,
        'options' => array(
          'id' => 'messages-pagination',
          'show_all' => false
        )
      )
    );
  ?>
</div>

<script>
  $(document).ready(function()
  {
    var $url = '<?= url_for('@messages_inbox') ?>';
    var $form = $('#inbox-form');

    $('#messages-pagination a').click(function(e)
    {
      e.preventDefault();
      var page = $(this).data('page');

      $('#messages-table').showLoading();
      $('#messages-table').load(
        $url + '?page=' + page,
        $form.serialize(),
        function() {
          $('#messages-table').hideLoading();
          APP.messages.inbox();
        }
      );

      return false;
    });
  });
</script>
