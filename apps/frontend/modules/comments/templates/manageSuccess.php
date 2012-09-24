<?php
  /* @var $comment Comment */
  /* @var $is_object_onwer boolean */

  $link = link_to_model_object('Back to comment thread >>', $comment);
  cq_page_title('Manage comment on "'.$comment->getModelObject().'"', $link, array());
?>

<div class="row-fluid">
  <h2>Manage comment</h2>

  <fieldset class="form-horizontal">
    <div class="user-comments span8">
      <?php include_partial('comments/single_comment', array('comment' => $comment, 'with_controls' => false, 'force_show' => true)); ?>
    </div>
    <div class="form-actions">
      <?php if ($is_object_owner): ?>
        <?php if (!$comment->getIsHidden()): ?>
          <?= link_to('Hide Comment', 'comments_hide', $comment, array('class' => 'btn btn-warning', 'post' => true)); ?>
        <?php else: ?>
          <?= link_to('Unhide Comment', 'comments_unhide', $comment, array('class' => 'btn btn-warning', 'post' => true)); ?>
        <?php endif; ?>
      <?php endif; ?>
      <a href="<?= url_for_model_object($comment); ?>" class="btn">Cancel</a>
    </div>
  </fieldset>
</div>