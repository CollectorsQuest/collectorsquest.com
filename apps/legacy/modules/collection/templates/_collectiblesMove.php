<?php
/**
 * @var  Collection[]   $collections
 * @var  Collectible[]  $collectibles
 * @var  Collection     $collection
 */

?>
<br class="clear" />

<div class="slider-wrapper cf ">
  <a href="#" class="prevPage browse left scrollable-big-buttons"></a>
<div class="scrollable-big">
  <ul>
    <?php
      foreach ($collections as $i => $c)
      {
        // Show the collection (in grid, list or hybrid view)
        include_partial(
          'collection/carousel_view_collection',
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


<div class="movable-collectibles-wrapper">
  <div id="movable-collectibles" class="movable-collectibles-container cf">
    <?php
        foreach ($collectibles as $i => $collectible)
        {
          echo '<div id="collectible-', $collectible->getId(), '" class="span-2 append-bottom thumb-space">',
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

  $('div.scrollable-big .loading').hide();
  $('div.scrollable-big ul').show();
  $("div.scrollable-big").jCarouselLite(
  {
    btnNext: "button.nextPage", btnPrev: "button.prevPage",
    mouseWheel: false, visible: 3, scroll: 2, circular: false, start: 0
  });

});
</script>
