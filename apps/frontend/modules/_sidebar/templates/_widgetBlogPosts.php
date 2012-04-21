<?php
/**
 * @var $wp_posts wpPost[]
 */
?>

<?php
  $link = link_to(
    'See all news &raquo;', 'blog/index',
    array('class' => 'text-v-middle link-align')
  );
  cq_sidebar_title('In Other News', $link, array('left' => 8, 'right' => 4));
?>

<?php foreach ($wp_posts as $wp_post): ?>

<!--
<div class="row-fluid bottom-margin">
  <div class="span5">
    <a href="/" title="">
      <?= ice_image_tag_flickholdr('140x110'); ?>
    </a>
  </div>
  <div class="span7">
    <strong>
      <a href="<?= $wp_post->getPostUrl(); ?>" class="link">
        <?= cqStatic::truncateText($wp_post->getPostTitle(), 40) ?>
      </a>
    </strong><br/>
    <span class="content">
      <?= $wp_post->getPostExcerpt(50, '...') ?>
    </span><br/>
    <small style="font-size: 80%">
      by <?= link_to_blog_author($wp_post->getwpUser(), 'text'); ?>
      <span style="color: grey"><?php echo time_ago_in_words($wp_post->getPostDate('U')) ?> ago</span>
    </small>
  </div>
</div>
//-->

<div class="row-fluid bottom-margin">
  <h4 style="margin-bottom: 5px;">
    <a href="<?= $wp_post->getPostUrl(); ?>" class="link">
      <?= cqStatic::truncateText($wp_post->getPostTitle(), 75) ?>
    </a>
  </h4>
  <span class="content">
    <?= $wp_post->getPostExcerpt(140, '...') ?>
  </span><br/>
  <small style="font-size: 80%">
    posted by <?= link_to_blog_author($wp_post->getwpUser(), 'text'); ?>
    <span style="color: grey"><?php echo time_ago_in_words($wp_post->getPostDate('U')) ?> ago</span>
  </small>
</div>

<?php endforeach; ?>
