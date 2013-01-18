<div class="homepage-featured">
  <h2 class="Chivo webfont">Articles</h2>

  <?php foreach ($blog_posts as $blog_post): ?>
    <a href="<?= $blog_post->getPostUrl(); ?>" class="link">
      <?= isset($title) ? $title : $blog_post->getPostTitle(); ?>
    </a>
    <p>posted by <?= link_to_blog_author($blog_post->getwpUser(), 'text'); ?></p>
  <?php endforeach; ?>
</div>
