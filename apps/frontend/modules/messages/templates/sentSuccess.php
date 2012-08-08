<?php
  /* @var $pager PropelModelPager */ $pager;

  cq_sidebar_title(
        'Sent', null,
  array('left' => 8, 'right' => 4, 'class'=>'mycq-red-title row-fluid messages-row indent-bottom15')
  );
  
  SmartMenu::setSelected('mycq_messages_sidebar', 'sent');
?>

<table class="private-messages-list table table-bordered">
  <tbody>
  <?php if (!$pager->isEmpty()): foreach ($pager->getResults() as $message): ?>
    <tr
      class="linkify <?= $message->getIsRead() ? 'read' : 'unread' ?>"
      data-url="<?= url_for('messages_show', $message); ?>"
    >
      <td class="sender-col">
        <?= image_tag_collector($message->getCollectorRelatedByReceiver(),
          '50x50', array('class' => 'avatar')); ?>
        To:&nbsp;<?= link_to_collector($message->getCollectorRelatedByReceiver()); ?>
        <p class="font10">
          <?= time_ago_in_words($message->getCreatedAt('U')); ?> ago
        </p>
      </td>
      <td class="message-col">
        <?= link_to($message->getSubject(), 'messages_show', $message); ?>
        <span>
          <?= Utf8::truncateHtmlKeepWordsWhole($message->getBody(), 100); ?>
        </span>
      </td>
    </tr>
  <?php endforeach; else: ?>
    <tr>
      <td colspan="5">You have not sent any messages</td>
    </tr>
  <?php endif; ?>
  </tbody>
</table>

<div class="row-fluid text-center">
  <?php
    include_component(
      'global', 'pagination', array('pager' => $pager)
    );
  ?>
</div>
