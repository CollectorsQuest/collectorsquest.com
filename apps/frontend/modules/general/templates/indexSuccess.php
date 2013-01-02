<div class="row">
  <div class="homepage-featured">
    <h2>Welcome to Collectors Quest!</h2>
    <p>
      Get the most out of your collections: post a gallery of your antiques, collectibles and
      vintage items to share and use as an archive; learn what’s going on in the collecting world,
      and meet other like-minded collectors.
    </p>
  </div>

  <div class="homepage-featured">
    <h2>Shop the Market</h2>
    <p>
      Buy and sell antiques, collectibles and vintage items quickly and easily in the CQ Market.
      <?= link_to('Learn more about selling on CQ', '@misc_guide_to_collecting'); ?> or
      <?= link_to('start shopping', '@marketplace'); ?>.
    </p>
  </div>

  <?php include_partial('general/homepage_blogpost_featured', array('blog_post' => $blog_posts[0])); ?>
  <?php include_partial('general/homepage_blogpost_featured', array('blog_post' => $blog_posts[1])); ?>

</div>

<?php cq_page_title("Now On Display") ?>

<div class="row">
  <div id="homepage" class="row-content">
    <?php
      for ($i = 0; $i < 9; $i++)
      if (isset($collectibles[$i]) && $collectibles[$i] instanceof Collectible)
      {
        include_partial(
          'collection/collectible_grid_view_square_small',
          array('collectible' => $collectibles[$i], 'i' => $collectibles[$i]->getId())
        );
      }
    ?>

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
      for ($i = 12; $i < 20; $i++)
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
        columnWidth : 140, gutterWidth: 15
      });
    });
  });
</script>
