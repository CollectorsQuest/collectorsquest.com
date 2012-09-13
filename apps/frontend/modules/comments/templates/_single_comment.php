<?php
  use_helper('Gravatar');
  /** @var Comment $comment */
?>

<div class="row-fluid user-comment" itemscope itemtype="http://schema.org/Comment">
  <div class="span2 text-right" itemprop="author" itemscope itemtype="http://schema.org/Person">
    <?php if (( $collector = $comment->getCollector() )): ?>
      <?php
        echo link_to(
          image_tag_collector($collector, '65x65', array('itemprop' => 'image')),
          url_for_collector($collector), array('absolute' => true, 'itemprop' => 'url')
        );
      ?>
    <?php else: ?>
      <?= gravatar_image_tag($comment->getAuthorEmail(), 65, 'G', sfConfig::get('sf_app') .'/multimedia/Collector/65x65.png') ?>
    <?php endif; ?>

    <?php // name is mandatory parameter for the Person item type ?>
    <span style="display: none;" itemprop = "name">
      <?php if ($collector): ?>
        <?= link_to_collector($collector); ?>
      <?php else: ?>
        <?= $comment->getAuthorName(); ?>
      <?php endif; ?>
    </span>
  </div>
  <div class="span10">
    <p id="comment-<?= $comment->getId(); ?>" class="bubble left">
      <?php if ($collector): ?>
        <span class="username"><?= link_to_collector($collector); ?></span>
      <?php else: ?>
        <span class="username"><?= $comment->getAuthorName(); ?></span>
      <?php endif; ?>

      <span itemprop="text">
        <?= $comment->getBody(); ?>
      </span>

      <?php if (!$comment->isPastCutoffDate()): ?>
      <span class="comment-time" itemprop="dateCreated">
        <?= time_ago_in_words_or_exact_date($comment->getCreatedAt()); ?>
      </span>
      <?php endif; ?>
    </p>
  </div>
</div>

<?php
    if (isset($height) && property_exists($height, 'value'))
    {
      //calculate approximately how many more than 1 rows are contained
      $comment_rows = (integer) (strlen($comment->getBody()) / 80);
      $height->value += 84 + 18 * $comment_rows;
    }
?>
