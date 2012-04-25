<?php
  /* @var $messages PrivateMessage[] */ $messages;
?>
<table class="private-messages-list table table-striped table-bordered table-condensed">
  <tbody>
  <?php if (count($messages)): foreach ($messages as $message): ?>
    <tr
      class="linkify <?= $message->getIsRead() ? 'read' : 'not-read' ?>"
      data-url="<?= url_for(array(
          'sf_route' => 'messages_show',
          'sf_subject' => $message,
        )); ?>"
    >
      <td class="sender-col">
        To: <?= link_to_collector($message->getCollectorRelatedByReceiver()); ?>
      </td>
      <td class="message-col">
        <?= link_to($message->getSubject(), array(
          'sf_route' => 'messages_show',
          'sf_subject' => $message,
        )); ?> -
        <span>
          <?= Utf8::truncateHtmlKeepWordsWhole($message->getBody(), 100); ?>
        </span>
        <small class="pull-right"><?= time_ago_in_words_or_exact_date($message->getCreatedAt()); ?></small>
      </td>
    </tr>
  <?php endforeach; else: ?>
    <tr>
      <td colspan="5">You have not sent any messages</td>
    </tr>
  <?php endif; ?>
  </tbody>
</table>
