<?php
/**
 * @var  wpPost[]    $blog_posts
 * @var  wpPost      $blog_post
 * @var  string      $blog_content
 * @var  Multimedia  $blog_image
 *
 * @var  Video[]  $latest_videos
 * @var  Video    $featured_video
 *
 * @var  Featured      $featured_week
 * @var  Collectible   $featured_collectible
 * @var  Collection[]  $latest_collections
 */
?>

<?php include_component('general', 'indexTheme'); ?>

<div class="clear">&nbsp;</div>
<div id="homepage" class="span-25" style="background: #F5F8DD;">
  <div class="span-7 box">
    <h2><?= __('ARTICLES'); ?></h2>

    <div style="height: 60px;">
      <h3><?= truncate_text(trim($blog_post->getPostTitle()), 80, '...', true); ?></h3>
      <span style="color: #466A48;">
        <?= sprintf(__('by %s'), link_to($blog_post->getwpUser()->getDisplayName(), '/blog/author/'. $blog_post->getwpUser()->getUserLogin().'/', array('style' => 'color: #466A48;'))); ?>
      </span>
    </div>

    <a href="<?= url_for('@blog'); ?>" title="<?= $blog_post->getPostTitle(); ?>">
      <?php echo image_tag_multimedia($blog_image, '300x225', array('alt' => $blog_post->getPostTitle(), 'style' => 'margin: 10px 0;')); ?>
    </a>
    <p style="height: 50px;">
      <?= truncate_text($blog_content, 140, '...', true); ?>
      <a href="<?= url_for('@blog'); ?>" title="<?= $blog_post->getPostTitle(); ?>">
        <?= __('(read the articles)'); ?>
      </a>
    </p>

    <br class="clear">
    <h3 style="color: #466A48;"><?= __('Latest Articles'); ?></h3>

    <ul class="articles">
    <?php foreach ($blog_posts as $blog_post): ?>
      <li>
        <a href="<?= $blog_post->getPostUrl(); ?>" title="<?= $blog_post->getPostTitle(); ?>">
          <?= truncate_text($blog_post->getPostTitle(), 37, '...', true); ?>
        </a>
      </li>
    <?php endforeach; ?>
    </ul>
    <div style='float: right; margin-right: 15px; font-size: 14px;'>
      <?= link_to(__('all blog articles'), '@blog'); ?>
    </div>
  </div>
  <div class="span-7 box">
    <h2><?= __('VIDEOS'); ?></h2>

    <div style="height: 60px;">
      <h3><?= truncate_text(trim($featured_video->getTitle()), 80, '...', true); ?></h3>
      <span style="color: #466A48;"><?= sprintf(__('%s Coverage'), $featured_video->getType()); ?></span>
    </div>

    <?= link_to_video($featured_video, 'image', array('width' => 300, 'height' => 225, 'style' => 'margin: 10px 0;')); ?>
    <p style="height: 50px;">
      <?= truncate_text($featured_video->getDescription(), 140, '...', true); ?>
      <a href="<?= url_for_video($featured_video); ?>" title="<?= $featured_video->getTitle(); ?>">
        <?= __('(watch the video)'); ?>
      </a>
    </p>

    <br class="clear">
    <h3 style="color: #466A48;"><?= __('Latest Videos'); ?></h3>

    <ul class="videos">
    <?php foreach ($latest_videos as $video): ?>
      <li>
        <?= link_to_video($video, 'text', array('truncate' => 40)); ?>
      </li>
    <?php endforeach; ?>
    </ul>
    <div style='float: right; margin-right: 15px; font-size: 14px;'>
      <?= link_to(__('all videos'), '@videos'); ?>
    </div>
  </div>
  <div class="span-7 box last">
    <h2><?= __('FEATURED WEEK'); ?></h2>

    <?php if ($featured_week): ?>
      <div style="height: 60px;">
        <h3><?= truncate_text(trim($featured_week->title), 80, '...', true); ?></h3>
      </div>

      <a href="<?= url_for_featured_week($featured_week); ?>" title="<?= addslashes($featured_week->title); ?>">
        <?= image_tag_collectible($featured_collectible, '300x225', array('width' => 300, 'height' => 225, 'style' => 'margin: 10px 0;')); ?>
      </a>
      <p style="height: 50px;">
        <?= truncate_text($featured_week->homepage_text, 145, '...', true); ?>
        <a href="<?= url_for_featured_week($featured_week); ?>" title="<?= addslashes($featured_week->title); ?>">
          <?= __('(see featured)'); ?>
        </a>
      </p>
    <?php else: ?>
      <div style="height: 378px;">&nbsp;</div>
    <?php endif; ?>

    <br class="clear">
    <h3 style="color: #466A48;"><?= __('Latest Collections'); ?></h3>

    <ul class="collections">
    <?php foreach ($latest_collections as $collection): ?>
      <li>
        <?= link_to_collection($collection, 'text', array('truncate' => 40)); ?>
      </li>
    <?php endforeach; ?>
    </ul>
    <div style='float: right; margin-right: 15px; font-size: 14px;'>
      <?= link_to(__('all collections'), '@collections'); ?>
    </div>
  </div>
</div>

<?php slot('footer'); ?>
<?php if (isset($collection_tags)): ?>
  <div class="span-25 tag-cloud append-bottom last" style="text-align: center;">
    <?php
      foreach($collection_tags as $tag => $count)
      {
        echo link_to(
          $tag,
          '@collections_by_tag?tag='. $tag .'&page=1',
          array('class'  => 'tag_popularity_'.($count+3))
        )." &nbsp; ";
      }
    ?>
  </div>
<?php endif; ?>
<?php end_slot(); ?>

<?php cq_javascript_tag(); ?>
<script type="text/javascript">
  $(document).ready(function()
  {
    $("#homepage ul a").bigTarget({
      hoverClass: 'pointer',
      clickZone : 'li:eq(0)'
    });
  });
</script>
<?php cq_end_javascript_tag(); ?>
