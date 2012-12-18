<?php
/* @var $blog_post wpPost */
/* @var $url       string|array */
?>

<div class="span6 brick masonry-blogpost featured-item">
  <div class="masonry-blogpost yellow-background">
    <a href="<?= url_for($url) ?>" class="link">
      <?php if (!isset($image) || $image === true): ?>
      <div class="featured-item-img">
        <?php
        if ($thumbnail = $blog_post->getPostThumbnail('homepage'))
        {
          echo cq_image_tag(
            $thumbnail, array('width' => 270, 'height' => 270, 'alt' => $blog_post->getPostTitle())
          );
        }
        else
        {
          echo image_tag_multimedia(
            $blog_post->getPrimaryImage(), '270x270',
            array('width' => 270, 'height' => 270, 'alt_title' => $blog_post->getPostTitle())
          );
        }
        ?>
      </div>
      <?php endif; ?>
      <h3 class="Chivo webfont" style="line-height: 26px;">
        <?= isset($title) ? $title : $blog_post->getPostTitle(); ?>
      </h3>
      <p>
        <?php
        echo isset($excerpt) ?
          $excerpt :
          cqStatic::truncateText($blog_post->getPlainPostContent(), 260, '...', true);
        ?>
      </p>
    </a>
  </div>
</div>
