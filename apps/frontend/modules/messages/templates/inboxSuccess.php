<div class="control-group clearfix">
  <div class="btn-group pull-left">
    <button class="btn">Mark as Read</button>
    <button class="btn">Mark as Unread</button>
    <button class="btn">Delete</button>
  </div>

  <div class="pull-right btn-group">
    <?= link_to('All', '@messages_inbox?filter=all', array('class' => 'btn '.('all' == $filter_by ? 'active' : '') )); ?>
    <?= link_to('Unread', '@messages_inbox?filter=unread', array('class' => 'btn '.('unread' == $filter_by ? 'active' : '') )); ?>
    <?= link_to('Read', '@messages_inbox?filter=read', array('class' => 'btn '.('read' == $filter_by ? 'active' : '') )); ?>
  </div> <!-- .pull-right -->
</div> <!-- .control-group -->

<table id="messages-inbox" class="table table-striped table-bordered table-condensed">
  <tbody>
  <?php if (count($messages)): foreach ($messages as $message): ?>
    <tr>
      <td><input type="checkbox" />
      <td><?= $message->getIsRead() ? 'Yes' : 'No'; ?>
      <td><?= $message->getCollectorRelatedBySender(); ?>
      <td><?= link_to($message->getSubject(), array(
          'sf_route' => 'messages_show',
          'sf_subject' => $message,
      )); ?>
      <td><?= mb_substr($message->getBody(), 0, 250, 'utf-8'); ?>

  <?php endforeach; else: ?>
    <tr>
      <td colspan="5">You have no messages in your inbox

  <?php endif; ?>
</table>