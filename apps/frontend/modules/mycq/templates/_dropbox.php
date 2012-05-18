<div class="tab-content-inner">
  <?php cq_section_title('Collectibles to Sort ('. $total .')') ?>
  <div class="collectibles-to-sort">
    <ul class="thumbnails">
      <?php foreach ($collectibles as $collectible): ?>
      <li class="span2 thumbnail" data-id="<?= $collectible->getId(); ?>">
        <?= image_tag_collectible($collectible, '75x75', array('max_width' => 72, 'max_height' => 72)); ?>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>

<div class="row-fluid instruction-box">
  <div class="span3">
    <span class="gray-arrow pull-right">&nbsp;</span>
  </div>
  <div class="span6 hint-text">
    <strong>Hint:</strong>&nbsp;<?= $instructions['text'] ?>
  </div>
  <div class="span3">
    <span class="gray-arrow">&nbsp;</span>
  </div>
</div><!-- /.instruction-box -->

<script type="text/javascript">
$(document).ready(function()
{
  $('.collectibles-to-sort li').draggable(
  {
    containment: '#contents',
    handle: 'img',
    opacity: 0.7,
    revert: true,
    cursor: 'move'
  });
});
</script>
