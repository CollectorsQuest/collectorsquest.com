<?php cq_page_title("Now On Display") ?>

<div class="row">
  <div id="homepage" class="row-content">
    <?php
      for ($i = 0; $i < 4; $i++)
      if (isset($collectibles[$i]) && $collectibles[$i] instanceof Collectible)
      {
        include_partial(
          'collection/collectible_grid_view_square_small',
          array('collectible' => $collectibles[$i], 'i' => $collectibles[$i]->getId())
        );
      }
    ?>

    <?php include_partial('general/homepage_blogpost', array('blog_post' => $blog_posts[0])); ?>

    <?php
      for ($i = 4; $i < 9; $i++)
      if (isset($collectibles[$i]) && $collectibles[$i] instanceof Collectible)
      {
        include_partial(
          'collection/collectible_grid_view_square_small',
          array('collectible' => $collectibles[$i], 'i' => $collectibles[$i]->getId())
        );
      }
    ?>

    <?php include_partial('general/homepage_blogpost', array('blog_post' => $blog_posts[1])); ?>

    <?php
      if (!empty($collections[0]) && $collections[0] instanceof Collection)
      {
        include_partial(
          'general/homepage_collection_tall',
          array('collection' => $collections[0])
        );
      }
    ?>

    <?php
      for ($i = 9; $i < 12; $i++)
      if (isset($collectibles[$i]) && $collectibles[$i] instanceof Collectible)
      {
        include_partial(
          'collection/collectible_grid_view_square_small',
          array('collectible' => $collectibles[$i], 'i' => $collectibles[$i]->getId())
        );
      }
    ?>

    <?php
      if (!empty($collections[1]) && $collections[1] instanceof Collection)
      {
        include_partial(
          'general/homepage_collection_wide',
          array('collection' => $collections[1])
        );
      }
    ?>

    <?php
      for ($i = 12; $i < 18; $i++)
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
    var $container = $('#homepage');

    $container.imagesLoaded(function()
    {
      $container.masonry(
      {
        itemSelector : '.brick, .span3, .span6',
        columnWidth : 140, gutterWidth: 15,
        isAnimated: !Modernizr.csstransitions
      });
    });
  });
</script>
