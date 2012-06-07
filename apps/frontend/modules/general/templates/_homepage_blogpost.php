<div class="span6 brick masonry-blogpost">
  <div class="masonry-blogpost yellow-background">
    <a href="<?= $blog_post->getPostUrl(); ?>" class="link">
      <div class="blog-img">
        <?php
          if ($thumbnail = $blog_post->getPostThumbnail('homepage'))
          {
            echo image_tag($thumbnail, array('width' => 270, 'height' => 270));
          }
          else
          {
            echo image_tag_multimedia($blog_post->getPrimaryImage(), '270x270');
          }
        ?>
      </div>
      <h3 class="Chivo webfont" style="line-height: 26px;"><?= $blog_post->getPostTitle(); ?></h3>
      <p><?= cqStatic::truncateText($blog_post->getPlainPostContent(), 160, '...', true); ?></p>
    </a>
    <div class="masonry-blogpost-author">
      <p>
        <?= link_to_blog_author($blog_post->getwpUser(), 'image', array('width' => 35, 'height' => 35, 'style' => 'float: left; margin-right: 10px;')); ?>
        posted by<br>
        <?= link_to_blog_author($blog_post->getwpUser(), 'text'); ?>
      </p>
    </div>
  </div>
</div>
