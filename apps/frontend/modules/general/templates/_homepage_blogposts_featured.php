<div class="homepage-featured blog_posts">
  <?php
  if ($thumbnail = $blog_posts[0]->getPostThumbnail('homepage'))
  {
    echo cq_image_tag(
      $thumbnail, array('size' => '90x90', 'alt' => $blog_posts[0]->getPostTitle(), 'style="margin-top: 15px;"')
    );
  }
  else
  {
    echo image_tag_multimedia(
      $blog_posts[0]->getPrimaryImage(), '90x90',
      array('size' => '90x90', 'alt_title' => $blog_posts[0]->getPostTitle(), 'style="margin-top: 15px;"')
    );
  }
  ?>

  <div style="float: left;">
    <h2 class="Chivo webfont">Latest from the Blog</h2>

    <?php foreach ($blog_posts as $blog_post): ?>
      <a href="<?= $blog_post->getPostUrl(); ?>" class="link">
        <?= isset($title) ? $title : $blog_post->getPostTitle(); ?>
      </a>
      <p>posted by <?= link_to_blog_author($blog_post->getwpUser(), 'text'); ?></p>
    <?php endforeach; ?>
  </div>

  <div class="clearfix"></div>
</div>
