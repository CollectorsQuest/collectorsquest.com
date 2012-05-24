<?php
  use_helper('Gravatar');
  /** @var Comment $comment */ $comment;
?>

<div class="user-comments">
  <?php foreach ($comments as $comment): ?>
  <div class="row-fluid user-comment">
    <div class="span2 text-right">
      <?php if (( $collector = $comment->getCollector() )): ?>
        <?= link_to(image_tag_collector($collector, '65x65'), url_for_collector($collector)); ?>
      <?php else: ?>
        <?= gravatar_image_tag($comment->getAuthorEmail(), 65, 'G', sfConfig::get('sf_app') .'/multimedia/Collector/65x65.png') ?>
      <?php endif; ?>
    </div>
    <div class="span10">
      <p class="bubble left">
        <?php if ($collector): ?>
          <?= link_to_collector($collector); ?>
        <?php else: ?>
          <span class="username"><?= $comment->getAuthorName(); ?></span>
        <?php endif; ?>

        <?= $comment->getBody(); ?>
        <span class="comment-time"><?= time_ago_in_words_or_exact_date($comment->getCreatedAt()); ?></span>
      </p>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<?php if (sfConfig::get('app_comments_num_load', 20) == $comments->count()): ?>
<div class="see-more-under-image-set">
  <button class="btn btn-small gray-button see-more-full" id="see-more-comments">
    See the next <?= sfConfig::get('app_comments_num_load', 20); ?> comments
  </button>
</div>
<?php endif; ?>
