<?php
/**
 * @var $video ContentEntry
 * @var $i integer
 */
use_javascript('jquery/mosaic.js');
use_stylesheet('frontend/mosaic.css');
?>

<div id="video_<?= $video->getId(); ?>_grid_view"
     data-id="<?= $video->getId(); ?>"
     class="video_grid_view fade-white">

  <div class="mosaic-overlay">
  <?= image_tag($video->getThumbnail(), array('class'=> 'mosaic-backdrop', 'style'=>'width: 190px; height: 150px;')); ?>
  </div>
</div>

<script>
  $(document).ready(function () {
    $('.fade-white').mosaic();
    $(".mosaic-overlay a.target").bigTarget({
      hoverClass:'over',
      clickZone:'div:eq(0)'
    });
  });
</script>
