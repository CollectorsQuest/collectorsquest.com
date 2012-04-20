<?php
  /**
   * @var $video ContentEntry
   * @var $i integer
   */
  use_javascript('jquery/mosaic.js');
?>

<div id="video_<?= $video->id; ?>_grid_view"
     data-id="<?= $video->id; ?>"
     class="video_grid_view fade-white">

  <div class="mosaic-overlay">
    <p class="details">
      <?= $video->getTitle(); ?>
    </p>
  </div>
  <?= image_tag($video->getThumbnail(), array('class'=> 'mosaic-backdrop', 'style'=>'width: 190px; height: 150px;')); ?>
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
