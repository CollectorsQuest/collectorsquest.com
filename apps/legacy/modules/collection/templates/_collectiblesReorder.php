<br class="clear" />
<div id="sortable-collectibles" style="margin-left: 40px; margin-right: -10px;">
<?php
  foreach ($collectibles as $i => $collectible)
  {
    echo '<div id="collectible-', $collectible->getId(), '" class="span-3 append-bottom">',
         image_tag_collectible($collectible, '75x75', array('style' => 'cursor: move;')),
         '</div>';
  }
?>
</div>

<br class="clear">
<div style="width: 200px; margin: auto;">
  <?php
    echo cq_button(
      __('Done re-ordering collectibles...'),
      route_for_collection($collection),
      array('class' => 'submit')
    );
  ?>
</div>

<script type="text/javascript">
$(document).ready(function()
{
  $('#sortable-collectibles').sortable(
  {
    items: 'div',
    handle: 'img',
    opacity: 0.7,
    revert: true,
    cursor: 'move',

    update: function()
    {
      $.post(
        '<?php echo url_for('@ajax_collection?section=reorder&page=collectibles&id='. $collection->getId()); ?>',
        {
          items: $('#sortable-collectibles').sortable('serialize'),
          key: 'collectible'
        }
      );
    }
  });
});
</script>
