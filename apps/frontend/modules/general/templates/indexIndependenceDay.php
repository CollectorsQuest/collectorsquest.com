<?php
/**
 * @var $collectibles Collectible[]
 */

/** @var $blog_post wpPost */
$blog_post = wpPostQuery::create()->findOneById(26075);

?>

<?php cq_page_title("Independence Day - 4th of July") ?>

<div class="row">
  <div id="homepage" class="row-content">

    <div class="span6 masonry-blogpost" style="width: 320px;">
      <a href="<?= $blog_post->getPostUrl(); ?>" class="link" style="width: auto; height: auto;">
        <div class="blog-img" style="width: 320px; height: 320px; padding: 0;">
        <?php
          if ($thumbnail = $blog_post->getPostThumbnail('original'))
          {
            echo image_tag($thumbnail, array('width' => 320, 'height' => 320));
          }
          else
          {
            echo image_tag('frontend/mockups/Untitled-2.jpg');
          }
        ?>
        </div>
        <h3 class="Chivo webfont" style="line-height: 26px;"><?= $blog_post->getPostTitle(); ?></h3>
        <p><?= cqStatic::truncateText($blog_post->getPlainPostContent(), 220, '...', true); ?></p>
      </a>
      <div class="masonry-blogpost-author">
        <p>
          <?php
            echo link_to_blog_author(
              $blog_post->getwpUser(), 'image',
              array('width' => 35, 'height' => 35, 'style' => 'float: left; margin-right: 10px;')
            );
          ?>
          posted by<br/>
          <?= link_to_blog_author($blog_post->getwpUser(), 'text'); ?>
        </p>
      </div>
    </div>

    <?php foreach ($collectibles as $i => $collectible): ?>
    <div class="span2" style="width: 100px; margin-bottom: 10px; <?= (($i-12)%7 !== 0 || $i < 12) ? 'margin-left: 10px' : ''; ?>">
      <?= link_to_collectible($collectible, 'image', array('width' => 100, 'height' => 100)); ?>
    </div>
    <?php endforeach; ?>

    <!-- This is for the stars of the flag to take two rows instead of 3
    <div class="span6" style="width: 320px;">
      <?= image_tag('frontend/mockups/Untitled-2.png') ?>
    </div>
    <?php foreach ($collectibles as $i => $collectible): ?>
    <div class="span2" style="width: 100px; margin-bottom: 10px; <?= (($i-8)%7 !== 0 || $i < 8) ? 'margin-left: 10px' : ''; ?>">
      <?= link_to_collectible($collectible, 'image', array('width' => 100, 'height' => 100)); ?>
    </div>
    <?php endforeach; ?>
    //-->

  </div>
</div>
