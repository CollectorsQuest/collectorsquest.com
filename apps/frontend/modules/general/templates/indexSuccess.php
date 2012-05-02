<?php cq_page_title("Now on Display: Check out these collectors' items!") ?>

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

    <div class="span3 brick">
      <div class="tall">
        <a href="#" class="link-brick">
          <?= ice_image_tag_flickholdr('140x295', array('i' => 2)) ?>
        </a>
        <div class="details" style="word-wrap: break-word;">
          <div class="details-inner">
            <h3>Art Deco Bronze Art Deco Bronze</h3>
            <p>More than meets the eye ore than meets the eye ore than meets the eye</p>
          </div>
          <img src="http://www.collectorsquest.next/images/legacy/multimedia/CollectorCollection/50x50.png" width="25" height="25" title="" alt="">          <p>ssdsdsdsdsdsssssssssssssssssssssssssssssssssssssssssssssssssssss</p>
        </div>
      </div>
    </div>

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

    <div class="span6 brick">
      <div class="wide">
        <a href="#" class="link-brick">
          <?= ice_image_tag_flickholdr('295x140', array('i' => 4)) ?>
        </a>
        <div class="details" style="word-wrap: break-word;">
          <div class="details-inner">
            <h3>Art Deco Bronze Art Deco Bronze</h3>
            <p>1 More than meets the eye ore than meets the eye ore than meets the eye</p>
          </div>
          <img src="http://www.collectorsquest.next/images/legacy/multimedia/CollectorCollection/50x50.png" width="25" height="25" title="" alt="">          <p>ssdsdsdsdsdsssssssssssssssssssssssssssssssssssssssssssssssssssss</p>
        </div>
      </div>
    </div>

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
