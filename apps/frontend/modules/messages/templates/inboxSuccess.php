<?php
/**
 * @var $messages PrivateMessage[] $messages;
 * @var $filter_by string read|unread|all $filter_by;
 */
?>

<form action="<?= url_for('@messages_batch_actions'); ?>" method="post">

  <div class="row-fluid">
    <div class="span8" style="padding-top: 5px;">
      <div class="checkbox-arrow pull-left"></div>
      <div class="private-messages-list-select control-group pull-left">
        <div class="btn-group">
          <button class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
            Select
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu">
            <li><a href="#" data-select="all">All</a></li>
            <li><a href="#" data-select="none">None</a></li>
            <li><a href="#" data-select="read">Read</a></li>
            <li><a href="#" data-select="unread">Unread</a></li>
          </ul>
        </div>
      </div>

      <div class="private-messages-list-actions control-group pull-left">
        <div class="btn-group ">
          <input type="submit" class="btn btn-mini" name="batch_action[mark_as_read]" value="Mark as Read" />
          <input type="submit" class="btn btn-mini" name="batch_action[mark_as_unread]" value="Mark as Unread" />
          <input type="submit" onclick="return confirm('Are you sure you sure you want to delete these messages?')" class="btn btn-mini" name="batch_action[delete]" value="Delete" />
        </div>
      </div>
    </div>

    <div class="span4">
      <span class="pull-left show-all-text">Show:</span>
      <div class="control-group pull-left">
        <div class=" btn-group">
          <?= link_to('All', '@messages_inbox?filter=all', array('class' => 'btn btn-cq '.('all' == $filter_by ? 'active' : '') )); ?>
          <?= link_to('Unread', '@messages_inbox?filter=unread', array('class' => 'btn btn-cq '.('unread' == $filter_by ? 'active' : '') )); ?>
          <?= link_to('Read', '@messages_inbox?filter=read', array('class' => 'btn btn-cq '.('read' == $filter_by ? 'active' : '') )); ?>
        </div>
      </div> <!-- .control-group.pull-left -->
    </div>
  </div>

  <table id="private-messages-inbox" class="private-messages-list table table-bordered">
    <tbody>
    <?php if (count($messages)): foreach ($messages as $message):
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
          <?= image_tag_collector($message->getCollectorRelatedBySender(),
            '50x50', array('class' => 'avatar')); ?>
          <?= link_to_collector($message->getCollectorRelatedBySender()); ?>
          <p style="font-size: 10px">
            <?= time_ago_in_words($message->getCreatedAt('U')); ?> ago
          </p>
        </td>
        <td class="message-col">
          <?= link_to($message->getSubject(), $message_link); ?>
          <span>
            <?= Utf8::truncateHtmlKeepWordsWhole($message->getBody(), 150); ?>
          </span>
        </td>
      </tr>
    <?php endforeach; else: ?>
      <tr>
        <td colspan="5">You have no messages in your inbox</td>
      </tr>
    <?php endif; ?>
    </tbody>
  </table>

</form>
