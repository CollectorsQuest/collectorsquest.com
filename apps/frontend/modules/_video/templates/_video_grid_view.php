<?php
/**
 * @var $video ContentEntry
 * @var $i integer
 */
?>

<div id="video_<?= $video->id; ?>_grid_view" data-id="<?= $video->id; ?>"
     class="video_grid_view fade-white link">
  <div class="mosaic-overlay">
    <p class="details"><?= $video->getTitle(); ?></p>
  </div>
  <a href="<?= $video->getPlayUrl() ?>" title="<?= $video->getTitle() ?>" class="target">
    <?= image_tag($video->getThumbnail(), array('class' => 'mosaic-backdrop')); ?>
  </a>
</div>
