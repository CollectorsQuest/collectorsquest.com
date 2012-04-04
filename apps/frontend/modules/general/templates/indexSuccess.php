<h2 class="FugazOne blue-title">
  &nbsp;&nbsp;Showcase
</h2>
<div class="row">
  <div id="homepage" class="row-content">
    <?php
      for ($i = 0; $i < 4; $i++)
      {
        include_partial('general/homepage_collectible', array('collectible' => $collectibles[$i], 'i' => $i));
      }
    ?>

    <div class="span6 brick masonry-blogpost">
      <div class="masonry-blogpost yellow-background">
        <a href="<?= $blog_posts[0]->getPostUrl(); ?>" class="link">
          <h3 class="Chivo" style="line-height: 26px;"><?= $blog_posts[0]->getPostTitle(); ?></h3>
          <p><?= cqStatic::truncateText($blog_posts[0]->getPostContentStripped(), 350, '...', true); ?></p>
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
      {
        include_partial('general/homepage_collectible', array('collectible' => $collectibles[$i], 'i' => $i));
      }
    ?>

    <div class="span6 brick masonry-blogpost">
      <div class="masonry-blogpost blue-background">
        <a href="<?= $blog_posts[1]->getPostUrl(); ?>" class="link">
          <h3 class="Chivo" style="line-height: 26px;"><?= $blog_posts[1]->getPostTitle(); ?></h3>
          <p><?= cqStatic::truncateText($blog_posts[1]->getPostContentStripped(), 350, '...', true); ?></p>
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

    <?php
      for ($i = 9; $i < 22; $i++)
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
