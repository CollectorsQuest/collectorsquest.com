<?php
  use_javascript('jquery/mousewheel.js');
  use_javascript('jquery/carousel.js');
?>

<ul style="list-style: none;">
<?php foreach ($videos as $i => $video): ?>
<li class="span-7 last video">
  <div style="float: left; margin: 5px;">
    <a href="javascript:playItem(<?php echo $i; ?>);">
      <?php echo image_tag($video->getThumbSmallSrc()); ?>
    </a>
  </div>
  <div>
    <span style='font-size: 12px; color: #70B1B7'>
      <a href="javascript:playItem(<?php echo $i; ?>);">
        <?php echo truncate_text($video->getTitle(), 24, '...', false); ?>
      </a>
    </span>
    <br>
    <span style='font-size: 12px;'>
      <?= wordwrap(truncate_text($video->getDescription(), 70, '...', true), 90, " "); ?>
    </span>
  </div>
</li>
<?php endforeach; ?>
</ul>

<?php cq_javascript_tag(); ?>
<script type="text/javascript">
$(document).ready(function()
{
  $("#playlist").jCarouselLite(
  {
    vertical: true,
    mouseWheel: true,
    circular: false,
    visible: 5,
    scroll: 1
  });
});
</script>
<?php cq_end_javascript_tag(); ?>
