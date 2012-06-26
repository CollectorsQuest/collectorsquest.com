<?php
  /** @var $for_object BaseObject */
  /** @var $comments Comment[] */
?>

<div class="user-comments">
  <?php foreach ($comments as $comment): ?>
    <?= include_partial('comments/single_comment', array('comment' => $comment)); ?>
  <?php endforeach; ?>
</div>

<?php if (sfConfig::get('app_comments_num_load', 20) == $comments->count()): ?>
<div class="see-more-under-image-set">
  <button class="btn btn-small see-more-full"
          id="load-more-comments"
          data-token="<?= CommentPeer::addCommentableTokenToSession($for_object, $sf_user); ?>"
          data-offset="<?= sfConfig::get('app_comments_num_load', 20); ?>"
          data-uri="<?= url_for('@comments_load_more'); ?>">
    See the next <?= sfConfig::get('app_comments_num_load', 20); ?> comments
  </button>
</div>
<?php endif; ?>
