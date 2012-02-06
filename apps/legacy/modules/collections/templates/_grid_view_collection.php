<?php use_javascript('jquery/bt.js'); ?>

<div id="grid_view_collection_<?php echo $collection->getId(); ?>"
     class="prepend-1 span-5 grid_view_collection <?php echo (isset($class)) ? $class : null; ?>"
     style="<?= (isset($style)) ? $style : null; ?>">

  <div class="stack">
    <?php echo link_to_collection($collection, 'image'); ?>
  </div>
  <p class="caption">
    <?php echo link_to_collection($collection, 'text'); ?>&nbsp;<font style="color:#ccc;">(<?php echo (int) $collection->countCollectibles(); ?>)</font>&nbsp;<?php if ($collection->countCollectiblesSince('7 day ago') > 0) echo image_tag('icons/new.png'); ?>
    <br><small>by</small>
    <?php echo link_to_collector($collection->getCollector(), 'text', array('style' => 'color: #000;')); ?>
  </p>
</div>

<?php cq_javascript_tag(); ?>
<script type="text/javascript">
$(document).ready(function()
{
  $('#grid_view_collection_<?php echo $collection->getId(); ?> .stack').bt(
  {
    ajaxPath: '<?= url_for('@ajax_collection_snapshot?id='. $collection->getId() .'&collectibles=3'); ?>',
    padding: 10,
    width: 250,
    spikeLength: 30,
    spikeGirth: 30,
    cornerRadius: 10,
    fill: 'rgb(244, 247, 220)',
    // fill: 'rgba(0, 0, 0, .9)',
    strokeWidth: 3,
    strokeStyle: '#BADC70',
    positions: ['top', 'bottom'],
    hoverIntentOpts:
    {
      interval: 800,
      timeout: 1500
    }
  });
});
</script>
<?php cq_end_javascript_tag(); ?>
