<div class="span6 brick masonry-blogpost">
  <div class="masonry-blogpost yellow-background">
    <a href="<?= $blog_post->getPostUrl(); ?>" class="link">
      <?php if (!isset($image) || $image === true): ?>
      <div class="blog-img">
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
            cqStatic::truncateText($blog_post->getPlainPostContent(), 160, '...', true);
        ?>
      </p>
    </a>
    <div class="masonry-blogpost-author">
      <p>
        <?php
          echo link_to_blog_author($blog_post->getwpUser(), 'image', array(
            'link_to' => array('style' => 'float: left; margin-right: 10px;'),
            'image_tag' => array('width' => 35, 'height' => 35, 'style' => 'float: left; margin-right: 10px;')
          ));
        ?>
        posted by<br>
        <?= link_to_blog_author($blog_post->getwpUser(), 'text'); ?>
      </p>
    </div>
  </div>
</div>
