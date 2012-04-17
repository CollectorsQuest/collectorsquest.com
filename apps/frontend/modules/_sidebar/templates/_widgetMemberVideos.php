<?php
/**
 * @var $video ContentEntry
 * @var $videos ContentFeed
 */
?>

<?php
  $link = '<a href="'. sfConfig::get('app_magnify_channel', 'collectors-quest.magnify.net') .'" class="text-v-middle link-align">See all &raquo;</a>';
  cq_sidebar_title('Member Videos', $link);
?>

<?php foreach ($videos as $video): ?>
<div class="row-fluid bottom-margin">
  <div class="span5">
    <div class="clip-inner">
      <a href="<?= $video->getIframeUrl() ?>" title="<?= $video->getTitle() ?>">
        <img src="<?= $video->getThumbnail() ?>" alt="<?= $video->getTitle() ?>" width="120" />
        <span class="sidebar-video-play-button"></span>
      </a>
    </div>
  </div>
  <div class="span7 max-height-video-box-sidebar">
    <div id="sidebar-videos">
        <span class="title">
          <a href="<?= $video->getIframeUrl() ?>" title="<?= $video->getTitle() ?>">
            <?= cqStatic::truncateText($video->getTitle(), 50) ?>
          </a>
        </span>
        <span class="content">
          <?= cqStatic::truncateText($video->getContent(), 50) ?>
        </span>
        <span class="updated-at"><?= $video->getUpdatedAt() ?></span>
    </div>
  </div>
</div>
<?php endforeach; ?>
