<?php use_javascript('jquery/bt.js'); ?>

<div id="list_view_collection_<?php echo $collection->getId(); ?>"
     class="prepend-1 span-17 list_view_collection <?php echo (isset($class)) ? $class : null; ?> last"
     style="<?= (isset($style)) ? $style : null; ?>">

  <div class="span-5 stack">
    <?php echo link_to_collection($collection, 'image'); ?>
  </div>
  <div class="span-12 details">
    <span style="font-size: 16px;"><?php echo link_to_collection($collection, 'text'); ?>&nbsp;<span style="color:#ccc;">(<?php echo (int) $collection->countCollectibles(); ?>)</span></span>
    &nbsp;<small>by</small>
    <?php echo link_to_collector($collection->getCollector(), 'text', array('style' => 'color: #000;')); ?>

    <br class="clear"><br>
    <?php echo truncate_text($collection->getDescription(), 250, ' [...]', true); ?>
  </div>
</div>

<?php cq_javascript_tag(); ?>
<script type="text/javascript">
$(document).ready(function()
{
  $('#list_view_collection_<?php echo $collection->getId(); ?> .stack').bt(
  {
    ajaxPath: '<?= url_for('@ajax_collection_snapshot?id='. $collection->getId() .'&collectibles=5'); ?>',
    padding: 10,
    width: 400,
    spikeLength: 30,
    spikeGirth: 30,
    cornerRadius: 10,
    fill: 'rgb(244, 247, 220)',
    // fill: 'rgba(0, 0, 0, .9)',
    strokeWidth: 3,
    strokeStyle: '#BADC70',
    positions: ['right', 'middle'],
    hoverIntentOpts:
    {
      interval: 800,
      timeout: 1500
    }
  });
});
</script>
<?php cq_end_javascript_tag(); ?>
