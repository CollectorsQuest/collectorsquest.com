<?php
  /** @var $messages PrivateMessage[] */ $messages;
  /** @var $filter_by string read|unread|all */ $filter_by;
?>

<?= form_tag('@messages_batch_actions'); ?>
<?php slot('message-controls'); ?>
<div class="private-messages-list-actions control-group clearfix">
  <div class="btn-group pull-left">
    <input type="submit" class="btn" name="batch_action[mark_as_read]" value="Mark as Read" />
    <input type="submit" class="btn" name="batch_action[mark_as_unread]" value="Mark as Unread" />
    <input type="submit" class="btn" name="batch_action[delete]" value="Delete" />
  </div>

  <div class="pull-right btn-group">
    <?= link_to('All', '@messages_inbox?filter=all', array('class' => 'btn '.('all' == $filter_by ? 'active' : '') )); ?>
    <?= link_to('Unread', '@messages_inbox?filter=unread', array('class' => 'btn '.('unread' == $filter_by ? 'active' : '') )); ?>
    <?= link_to('Read', '@messages_inbox?filter=read', array('class' => 'btn '.('read' == $filter_by ? 'active' : '') )); ?>
  </div> <!-- .pull-right -->
</div> <!-- .control-group -->
<?php end_slot(); ?>

<?php include_slot('message-controls'); ?>

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
      <td class="select-col dont-linkify"><input type="checkbox" name="ids[]" value="<?= $message->getId() ?>" /></td>
      <td class="sender-col"><?= $message->getCollectorRelatedBySender(); ?></td>
      <td class="message-col">
        <?= link_to($message->getSubject(), array(
          'sf_route' => 'messages_show',
          'sf_subject' => $message,
        )); ?>
        <span>
          <?= Utf8::truncateHtmlKeepWordsWhole($message->getBody(), 200); ?>
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

<?php if (count($messages) > 25): ?>
  <?php include_slot('message-controls'); ?>
<?php endif; ?>

</form>