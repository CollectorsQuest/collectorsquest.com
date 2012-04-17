<div class="header-bar">
  <h2 class="Chivo webfont">Showcase</h2>
</div>
<div class="row">
  <div id="homepage" class="row-content">
    <?php
      for ($i = 0; $i < 4; $i++)
      if (isset($collectibles[$i]) && $collectibles[$i] instanceof Collectible)
      {
        include_partial('general/homepage_collectible', array('collectible' => $collectibles[$i], 'i' => $i));
      }
    ?>

    <?php include_partial('general/homepage_blogpost', array('blog_post' => $blog_posts[0])); ?>

    <?php
      for ($i = 4; $i < 9; $i++)
      if (isset($collectibles[$i]) && $collectibles[$i] instanceof Collectible)
      {
        include_partial('general/homepage_collectible', array('collectible' => $collectibles[$i], 'i' => $i));
      }
    ?>

    <?php include_partial('general/homepage_blogpost', array('blog_post' => $blog_posts[1])); ?>

    <div class="span3 brick">
      <div class="tall">
        <a href="#" class="link-brick">
          <?= ice_image_tag_flickholdr('138x290', array('i' => 2)) ?>
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
        include_partial('general/homepage_collectible', array('collectible' => $collectibles[$i], 'i' => $i));
      }
    ?>

    <div class="span6 brick">
      <div class="wide">
        <a href="#" class="link-brick">
          <?= ice_image_tag_flickholdr('290x138', array('i' => 4)) ?>
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
        include_partial('general/homepage_collectible', array('collectible' => $collectibles[$i], 'i' => $i));
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
        itemSelector : '.brick',
        columnWidth : 138, gutterWidth: 15,
        isAnimated: !Modernizr.csstransitions
      });
    });
  });
</script>
