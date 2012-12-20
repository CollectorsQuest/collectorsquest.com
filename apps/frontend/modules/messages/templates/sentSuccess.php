<?php
  /* @var $pager PropelModelPager */
  /* @var $message PrivateMessage */

  cq_sidebar_title(
    'Sent Messages', null,
    array(
      'left' => 8, 'right' => 4,
      'class'=>'mycq-red-title row-fluid messages-header spacer-bottom-15'
    )
  );

  SmartMenu::setSelected('mycq_messages_sidebar', 'sent');
?>

<table class="private-messages-list table table-bordered">
  <tbody>
  <?php if (!$pager->isEmpty()): foreach ($pager->getResults() as $message): ?>
    <tr class="linkify <?= $message->getIsRead() ? 'read' : 'unread' ?>"
        data-url="<?= url_for('messages_show', $message); ?>">
      <td class="sender-col">
        <?php
          echo image_tag_collector(
            $message->getCollectorRelatedByReceiverId(), '50x50', array('class' => 'avatar')
          );
        ?>
        To:&nbsp;<?= link_to_collector($message->getCollectorRelatedByReceiverId()) ?: mail_to($message->getReceiverEmail()); ?>
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
      <td colspan="5">
        You have not sent any messages yet.
        Do you want to <?= link_to('compose a new message', '@messages_compose') ?> now?
      </td>
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
