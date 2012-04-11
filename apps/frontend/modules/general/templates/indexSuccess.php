<h2 class="Chivo webfont red-title">
  Showcase
</h2>
<div class="row">
  <div id="homepage" class="row-content">
    <?php
      for ($i = 0; $i < 4; $i++)
      if (isset($collectibles[$i]) && $collectibles[$i] instanceof Collectible)
      {
        include_partial('general/homepage_collectible', array('collectible' => $collectibles[$i], 'i' => $i));
      }
    ?>

    <div class="span6 brick masonry-blogpost">
      <div class="masonry-blogpost yellow-background">
        <a href="<?= $blog_posts[0]->getPostUrl(); ?>" class="link">
          <h3 class="Chivo webfont" style="line-height: 26px;"><?= $blog_posts[0]->getPostTitle(); ?></h3>
          <p><?= cqStatic::truncateText($blog_posts[0]->getPlainPostContent(), 350, '...', true); ?></p>
        </a>
        <div class="masonry-blogpost-author">
          <p>
            <?= link_to_blog_author($blog_posts[0]->getwpUser(), 'image', array('width' => 35, 'height' => 35, 'style' => 'float: left; margin-right: 10px;')); ?>
            posted by<br>
            <?= link_to_blog_author($blog_posts[0]->getwpUser(), 'text'); ?>
          </p>
        </div>
      </div>
    </div>

    <?php
      for ($i = 4; $i < 9; $i++)
      if (isset($collectibles[$i]) && $collectibles[$i] instanceof Collectible)
      {
        include_partial('general/homepage_collectible', array('collectible' => $collectibles[$i], 'i' => $i));
      }
    ?>

    <div class="span6 brick masonry-blogpost">
      <div class="masonry-blogpost blue-background">
        <a href="<?= $blog_posts[1]->getPostUrl(); ?>" class="link">
          <h3 class="Chivo webfont" style="line-height: 26px;"><?= $blog_posts[1]->getPostTitle(); ?></h3>
          <p><?= cqStatic::truncateText($blog_posts[1]->getPlainPostContent(), 350, '...', true); ?></p>
        </a>
        <div class="masonry-blogpost-author">
          <p>
            <?= link_to_blog_author($blog_posts[1]->getwpUser(), 'image', array('width' => 35, 'height' => 35, 'style' => 'float: left; margin-right: 10px;')); ?>
            posted by<br>
            <?= link_to_blog_author($blog_posts[1]->getwpUser(), 'text'); ?>
          </p>
        </div>
      </div>
    </div>

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
