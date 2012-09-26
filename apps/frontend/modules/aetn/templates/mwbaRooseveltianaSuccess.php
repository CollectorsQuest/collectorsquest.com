<?php
/**
 * @var $collectibles Collectible[]
 */
?>
<div class="row">
  <div id="mwba_collectibles" class="row-content">
    <?php
    // set positions which will have the corresponding partials
    $small = array ( 0,  2,  3,  6,  7, 10, 11, 12, 13, 14, 15, 17, 18, 19, 20, 21, 22, 23);
    $wide  = array ( 1, 16 );
    $tall  = array ( 4,  8 );
    $big   = array ( 5,  9 );

    foreach ($collectibles as $i => $collectible)
    {
      // set the link to open modal dialog
      $link = link_to($collectible->getName(), 'ajax_aetn',
        array(
          'section' => 'mwbaCollectible',
          'page' => 'show',
          'id' => $collectible->getId()
        ),
        array('class' => 'open-dialog', 'onclick' => 'return false;')
      );

      // which partial we want to show the Collectible with
      $partial = '';
      if (in_array($i, $small))
      {
        $partial = 'square_small';
      }
      else if (in_array($i, $wide))
      {
        $partial = 'wide';
      }
      else if (in_array($i, $tall))
      {
        $partial = 'tall';
      }
      else if (in_array($i, $big))
      {
        $partial = 'square_big';
      }

      include_partial(
        'collection/collectible_grid_view_' . $partial,
        array(
          'collectible' => $collectible, 'i' => $collectible->getId(),
          'link' => $link
        )
      );
    }
    ?>
  </div>
</div>

<script>
  $(document).ready(function()
  {
    var $container = $('#mwba_collectibles');

    $container.imagesLoaded(function()
    {
      $container.masonry(
        {
          itemSelector : '.span3, .span6',
          columnWidth : 140, gutterWidth: 15,
          isAnimated: !Modernizr.csstransitions
        });
    });
  });
</script>
