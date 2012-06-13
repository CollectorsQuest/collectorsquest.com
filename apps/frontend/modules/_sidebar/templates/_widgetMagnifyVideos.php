<?php
/**
 * @var $video ContentEntry
 * @var $videos ContentFeed
 */
?>

<?php
  $link = link_to(
    'See all video &raquo;',
    'http://'. sfConfig::get('app_magnify_channel', 'video.collectorsquest.com'),
    array('class' => 'text-v-middle link-align')
  );
  cq_sidebar_title('Now Playing', $link, array('left' => 8, 'right' => 4));
?>

<?php foreach ($videos as $video): ?>
<div class="row-fluid spacer-bottom">
  <div class="span5">
    <div class="clip-inner">
      <a href="<?= $video->getPlayUrl() ?>" title="<?= $video->getTitle() ?>">
        <img src="<?= $video->getThumbnail() ?>" alt="<?= $video->getTitle() ?>" width="120" />
        <span class="sidebar-video-play-button"></span>
      </a>
    </div>
  </div>
  <div class="span7 max-height-video-box-sidebar">
    <div id="sidebar-videos">
        <span class="title">
          <a href="<?= $video->getPlayUrl() ?>" title="<?= $video->getTitle() ?>">
            <?= cqStatic::truncateText($video->getTitle(), 50) ?>
          </a>
        </span>
        <span class="content">
          <?= cqStatic::truncateText($video->getContent(), 75) ?>
        </span>
    </div>
  </div>
</div>
<?php endforeach; ?>
