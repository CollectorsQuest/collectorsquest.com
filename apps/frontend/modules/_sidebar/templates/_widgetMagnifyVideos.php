<?php
/**
 * @var $video  ContentEntry
 * @var $videos ContentFeed
 * @var $height stdClass
 */

$_height = 0;
?>

<?php
  $link = link_to(
    'See all video &raquo;',
    'http://'. sfConfig::get('app_magnify_channel', 'video.collectorsquest.com'),
    array('class' => 'text-v-middle link-align')
  );
  cq_sidebar_title('Now Playing', $link, array('left' => 8, 'right' => 4));
  $_height -= 63;
?>

<?php foreach ($videos as $video): ?>
<div class="row-fluid spacer-bottom">
  <div class="span5 sidebar-video-image">
    <div class="clip-inner">
      <a href="<?= $video->getPlayUrl() ?>" title="<?= $video->getTitle() ?>">
        <img src="<?= $video->getThumbnail() ?>" alt="<?= $video->getTitle() ?>" width="120" />
        <span class="sidebar-video-play-button"></span>
      </a>
    </div>
  </div>
  <div class="span7 max-height-video-box-sidebar">
    <div class="sidebar-videos">
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

<?php
  $_height -= 100;
  endforeach;

  if (isset($height) && property_exists($height, 'value'))
  {
    $height->value -= abs($_height);
  }
?>
