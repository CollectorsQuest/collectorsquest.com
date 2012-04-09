<?php
/**
 * @var $video ContentEntry
 * @var $videos ContentFeed
 */
?>

<?
  $link = '<a href="'. sfConfig::get('app_magnify_channel', 'collectors-quest.magnify.net') .'">See all &raquo;</a>';
  cq_sidebar_title('Member Videos', $link);
?>

<?php foreach ($videos as $video): ?>
<div class="row-fluid bottom-margin">
    <div class="span5">
      <div class="clip-inner">
        <img src="<?php echo $video->getThumbnail() ?>" alt="<?php echo $video->getTitle() ?>" width="120" style="flaot: left;" />
        <a href="<?php echo $video->getIframeUrl() ?>" title="<?php echo $video->getTitle() ?>">
          <span class="sidebar-video-play-button"></span>
        </a>
      </div>
    </div>
    <div class="span7 max-height-video-box-sidebar">
      <div id="sidebar-videos">
        <span class="title">
          <a href="<?php echo $video->getIframeUrl() ?>" title="<?php echo $video->getTitle() ?>">
            <?php echo $video->getTitle() ?>
          </a>
        </span>
        <span class="content">
          <?php echo cqStatic::truncateText($video->getContent(), 50); ?>
        </span>
        <span class="updated-at">
        <?php echo $video->getUpdatedAt() ?>
        </span>
      </div>
    </div>
  </div>
<?php endforeach; ?>
