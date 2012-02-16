<?php
/**
 * @var  Collection[]   $collections
 * @var  Collectible[]  $collectibles
 * @var  Collection     $collection
 */
?>

<br class="clear" />
<?php
  foreach ($collections as $i => $c)
  {
    // Show the collection (in grid, list or hybrid view)
    include_partial(
      'collections/grid_view_collection',
      array(
        'collection' => $c,
        'culture' => $sf_user->getCulture(),
        'i' => $i
      )
    );
  }
?>

<hr/>

<br class="clear" />
<div id="movable-collectibles" style="margin-left: 40px; margin-right: -10px;">
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
      __('Done moving collectibles...'),
      route_for_collection($collection),
      array('class' => 'submit')
    );
  ?>
</div>

<script type="text/javascript">
$(document).ready(function()
{
  $('#movable-collectibles').sortable(
  {
    items: 'div',
    handle: 'img',
    opacity: 0.7,
    revert: true,
    cursor: 'move',

    update: function()
    {
      $.post(
        '<?php echo url_for('@ajax_collection?section=move&page=collectibles&id='. $collection->getId()); ?>',
        {
          items: $('#movable-collectibles').sortable('serialize'),
          key: 'collectible'
        }
      );
    }
  });
});
</script>
