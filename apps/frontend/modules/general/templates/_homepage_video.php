<?php
/**
 * @var $video MagnifyResource
 */
?>

<div class="span3 brick" style="background: #000; padding: 18px 0;">
  <a href="<?= $video->getPlayUrl() ?>" title="<?= $video->getTitle() ?>">
    <img src="<?= $video->getThumbnail() ?>" alt="<?= $video->getTitle() ?>" width="140" />
    <span class="sidebar-video-play-button"></span>
  </a>
</div>
