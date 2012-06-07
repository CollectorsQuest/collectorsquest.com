<?php include_partial('emails/header'); ?>

<p style="margin-bottom: 10px; font-weight: bold;">
  <?= sprintf(__('Dear %s', array(), 'emails'), $collector->getDisplayName()); ?>,
</p>
<p>
  There is a new comment on your collection <?= link_to_collection($comment->getCollection(), 'text', array('absolute' => true)); ?>
  from <strong><?= $author_name; ?></strong>.
  You can go to the <?= link_to('Comment', '@comment_by_id?id='. $comment->getId(), array('absolute' => true)); ?> to read it and reply.
</p>

<?php include_partial('emails/footer'); ?>
