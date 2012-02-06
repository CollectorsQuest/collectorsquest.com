<?php include_partial('emails/header'); ?>

<p style="margin-bottom: 10px; font-weight: bold;">
  <?= sprintf(__('Dear %s', null, 'emails'), $receiver->getDisplayName()); ?>,
</p>
<p>
  You have a new private message on <?= link_to("Collectors' Quest", '@homepage', array('absolute' => true)); ?>
  from user <?= link_to_collector($sender, 'text', array('absolute' => true)); ?>.
  You can go to your <?= link_to('Messages Inbox', '@messages_inbox', array('absolute' => true)); ?> to read it and reply.
</p>

<?php include_partial('emails/footer'); ?>
