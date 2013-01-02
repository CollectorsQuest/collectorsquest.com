<div class="homepage-featured">
  <h2>
    <a href="<?= $blog_post->getPostUrl(); ?>" class="link">
      <?= isset($title) ? $title : $blog_post->getPostTitle(); ?>
    </a>
  </h2>

  <?php
    if ($thumbnail = $blog_post->getPostThumbnail('homepage'))
    {
      echo cq_image_tag(
        $thumbnail, array('width' => 75, 'height' => 75, 'alt' => $blog_post->getPostTitle())
      );
    }
    else
    {
      echo image_tag_multimedia(
        $blog_post->getPrimaryImage(), '75x75',
        array('width' => 75, 'height' => 75, 'alt_title' => $blog_post->getPostTitle())
      );
    }
  ?>

  <p>
    <?php
    echo isset($excerpt) ?
      $excerpt :
      cqStatic::truncateText($blog_post->getPlainPostContent(), 200, '...', true);
    ?>
  </p>
</div>
