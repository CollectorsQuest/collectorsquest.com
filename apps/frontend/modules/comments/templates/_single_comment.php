<?php
  use_helper('Gravatar');
  /* @var $comment Comment*/
  $force_show = isset($force_show) ? $force_show : false;
  $with_controls = isset($with_controls) ? $with_controls : false;
?>

<div id="comment-<?= $comment->getId(); ?>" class="row-fluid user-comment">
<?php if (!$comment->getIsHidden() || $force_show): ?>
  <div class="span2 text-right">
    <?php if (( $collector = $comment->getCollector() )): ?>
      <?= link_to(image_tag_collector($collector, '65x65'), url_for_collector($collector), array('absolute'=>true)); ?>
    <?php else: ?>
      <?= gravatar_image_tag($comment->getAuthorEmail(), 65, 'G', sfConfig::get('sf_app') .'/multimedia/Collector/65x65.png') ?>
    <?php endif; ?>
  </div>
  <div class="span10">
    <div class="bubble left clearfix">
      <div class="clearfix">
        <?php if ($collector): ?>
        <span class="username pull-left"><?= link_to_collector($collector); ?></span>
        <?php else: ?>
        <span class="username pull-left"><?= $comment->getAuthorName(); ?></span>
        <?php endif; ?>
        <?php if ($with_controls): ?>
        <div class="dropdown pull-right">
          <a class="dropdown-toggle manage-button block" href="<?= url_for('comments_manage', $comment); ?>"
             role="button" title="Manage comment" aria-label="Manage comment" data-toggle="dropdown" data-target="#">
            <b class="caret no-js-hide"></b> <i class="icon-edit"></i>
          </a>
          <ul class="dropdown-menu" role="menu" aria-labelledby="comment-actions" >
            <li><?= link_to('<i class="icon-remove"></i> Hide Comment</a>','comments_hide', $comment, array('post' => true)); ?></li>
          </ul>
        </div>
        <?php endif; ?>
      </div>
      <p>
        <?= $comment->getBody(); ?>
      </p>
      <?php if (!$comment->isPastCutoffDate()): ?>
      <span class="comment-time" title="<?= $comment->getCreatedAt('c'); ?>">
        <?= time_ago_in_words_or_exact_date($comment->getCreatedAt()); ?>
      </span>
      <?php endif; ?>
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
<?php elseif ($sf_user->isOwnerOf($comment->getModelObject())): ?>
  <div class="bubble full-length text-center">
    <span class="text-center">
    Comment hidden by you.
    <?= link_to('(Unhide)', 'comments_unhide', $comment); ?>
    </span>
  </div>
  <?php
    if (isset($height) && property_exists($height, 'value'))
    {
      $height->value += 42;
    }
  ?>
<?php endif; ?>
</div>
