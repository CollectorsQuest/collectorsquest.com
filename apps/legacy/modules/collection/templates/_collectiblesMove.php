<?php
/**
 * @var  Collection[]   $collections
 * @var  Collectible[]  $collectibles
 * @var  Collection     $collection
 */

?>
<br class="clear" />

<div class="slider-wrapper cf">
  <a href="#" class="prevPage browse left scrollable-big-buttons"></a>
<div class="scrollable-big">
  <ul>
    <?php
      foreach ($collections as $i => $c)
      {
        include_partial(
          'collections/carousel_view_collection',
          array(
            'collection' => $c,
            'culture' => $sf_user->getCulture(),
            'i' => $i
          )
        );
      }
    ?>
  </ul>
</div>
  <a href="#" class="nextPage browse right scrollable-big-buttons"></a>
</div>


<div class="draggable-collectibles-wrapper">
  <div class="draggable-collectibles-admination">
   <img src="/images/legacy/drag_drop_animation.gif" width="104" height="73" alt="drag and drop images into collections" />
   <p>drag and drop<br />images into collections</p>
  </div>
  <div id="draggable-collectibles" class="draggable-collectibles-container cf">
    <?php
        foreach ($collectibles as $i => $collectible)
        {
          echo '<div id="collectible-', $collectible->getId(), '" data-id="', $collectible->getId() ,'" class="span-2 append-bottom thumb-space">',
               image_tag_collectible($collectible, '75x75', array('style' => 'cursor: move;')),
               '</div>';
        }
    ?>
  </div>
</div>
<br class="clear">
<div style="width: 200px; margin:20px auto;">
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
  $('#draggable-collectibles div').draggable(
  {
    containment: '#contents',
    handle: 'img',
    opacity: 0.7,
    revert: true,
    cursor: 'move'
  });

  $('div.scrollable-big .loading').hide();
  $('div.scrollable-big ul').show();
  $("div.scrollable-big").jCarouselLite(
  {
    btnNext: "a.nextPage", btnPrev: "a.prevPage",
    mouseWheel: true, visible: 3, scroll: 2, circular: true, start: 0
  });

  $("div.grid_view_collection").droppable(
  {
    over: function(event, ui)
    {
      $(this).addClass("ui-state-highlight");
    },
    out: function(event, ui)
    {
      $(this).removeClass("ui-state-highlight");
    },
    drop: function(event, ui)
    {
      $(this).removeClass("ui-state-highlight");
      ui.draggable.draggable( 'option', 'revert', false );

      $.ajax(
      {
        url: '<?php echo url_for('@ajax_collection?section=move&page=collectibles'); ?>',
        type: 'GET',
        data: { collectible_id: ui.draggable.data('id'), from: '<?= $collection->getId(); ?>', to: $(this).data('id') },
        success: function()
        {
          ui.draggable.draggable('option', 'revert', false);
          ui.draggable.hide();
        },
        error: function()
        {
          ui.draggable.draggable('option', 'revert', true);
          ui.draggable.show();
        }
      });
    }
  });
});
</script>
