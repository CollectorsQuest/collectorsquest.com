<?php
/**
 * @var $wp_posts wpPost[]
 * @var $title string
 */
?>

<?php
  $link = link_to(
    'See all news &raquo;', 'blog/index',
    array('class' => 'text-v-middle link-align')
  );
  cq_sidebar_title($title, $link, array('left' => 8, 'right' => 4));
?>

<?php foreach ($wp_posts as $wp_post): ?>
<div class="row-fluid spacer-bottom">
  <h4 class="spacer-bottom-5">
    <a href="<?= $wp_post->getPostUrl(); ?>" class="link">
      <?= cqStatic::truncateText($wp_post->getPostTitle(), 75) ?>
    </a>
  </h4>
  <span class="content">
    <?= $wp_post->getPostExcerpt(140, '...') ?>
  </span><br/>
  <small>
    posted by <?= link_to_blog_author($wp_post->getwpUser(), 'text'); ?>
    <span class="grey"><?php echo time_ago_in_words($wp_post->getPostDate('U')) ?> ago</span>
  </small>
</div>
<?php endforeach; ?>
