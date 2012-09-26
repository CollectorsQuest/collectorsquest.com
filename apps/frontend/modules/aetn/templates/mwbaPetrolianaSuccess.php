<?php
/**
 * @var $collectibles Collectible[]
 */
?>
<div class="row">
  <div id="mwba_collectibles" class="row-content">
    <?php
      if (isset($collectibles[0]) && $collectibles[0] instanceof Collectible)
      {
        include_partial(
          'collection/collectible_grid_view_square_small',
          array('collectible' => $collectibles[0], 'i' => $collectibles[0]->getId())
        );
      }

      if (isset($collectibles[1]) && $collectibles[1] instanceof Collectible)
      {
        include_partial(
          'collection/collectible_grid_view_wide',
          array(
            'collectible' => $collectibles[1], 'i' => $collectibles[1]->getId(),
            'open_dialog' => true
          )
        );
      }

      for ($i = 2; $i < 4; $i++)
        if (isset($collectibles[$i]) && $collectibles[$i] instanceof Collectible)
        {
          include_partial(
            'collection/collectible_grid_view_square_small',
            array('collectible' => $collectibles[$i], 'i' => $collectibles[$i]->getId())
          );
        }

      if (isset($collectibles[4]) && $collectibles[4] instanceof Collectible)
      {
        include_partial(
          'collection/collectible_grid_view_tall',
          array('collectible' => $collectibles[4], 'i' => $collectibles[4]->getId())
        );
      }

      if (isset($collectibles[5]) && $collectibles[5] instanceof Collectible)
      {
        include_partial(
          'collection/collectible_grid_view_square_big',
          array('collectible' => $collectibles[5], 'i' => $collectibles[5]->getId())
        );
      }

      for ($i = 6; $i < 8; $i++)
        if (isset($collectibles[$i]) && $collectibles[$i] instanceof Collectible)
        {
          include_partial(
            'collection/collectible_grid_view_square_small',
            array('collectible' => $collectibles[$i], 'i' => $collectibles[$i]->getId())
          );
        }

      if (isset($collectibles[8]) && $collectibles[8] instanceof Collectible)
      {
        include_partial(
          'collection/collectible_grid_view_tall',
          array('collectible' => $collectibles[8], 'i' => $collectibles[8]->getId())
        );
      }

      for ($i = 9; $i < 20; $i++)
        if (isset($collectibles[$i]) && $collectibles[$i] instanceof Collectible)
        {
          include_partial(
            'collection/collectible_grid_view_square_small',
            array('collectible' => $collectibles[$i], 'i' => $collectibles[$i]->getId())
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
