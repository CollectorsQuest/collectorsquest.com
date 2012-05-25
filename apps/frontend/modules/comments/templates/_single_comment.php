<?php
  use_helper('Gravatar');
  /** @var Comment $comment */ $comment;
?>

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
        <span class="username"><?= link_to_collector($collector); ?></span>
      <?php else: ?>
        <span class="username"><?= $comment->getAuthorName(); ?></span>
      <?php endif; ?>

      <?= $comment->getBody(); ?>
      <span class="comment-time">
        <?= time_ago_in_words_or_exact_date($comment->getCreatedAt()); ?>
      </span>
    </p>
  </div>
</div>
