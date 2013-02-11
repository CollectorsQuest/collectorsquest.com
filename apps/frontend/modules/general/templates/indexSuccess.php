
<div class="row">
  <div id="main_homepage">
    <div class="homepage-featured">
      <?= ($cms_slot1 instanceof wpPost) ? $cms_slot1->getPostContent() : null; ?>
    </div>

    <div class="homepage-featured">
      <?= ($cms_slot2 instanceof wpPost) ? $cms_slot2->getPostContent() : null; ?>
    </div>

    <?php include_partial('general/homepage_blogposts_featured', array('blog_posts' => $blog_posts)); ?>
    <br>
  </div>

  <div id="sidebar_homepage">
    <?= ($cms_slot3 instanceof wpPost) ? $cms_slot3->getPostContent() : null; ?>
    <br>
  </div>
</div>

<?php cq_page_title('Now On Display') ?>

<div class="row">
  <div id="homepage" class="row-content">
    <?php
      $k = rand(2, 5);
      foreach ($collectibles as $i => $collectible)
      {
        if ($i % $k === 0 && isset($videos[intval($i / $k)]))
        {
          include_partial(
            'general/homepage_video',
            array('video' => $videos[intval($i / $k)], 'i' => $i)
          );
        }

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
