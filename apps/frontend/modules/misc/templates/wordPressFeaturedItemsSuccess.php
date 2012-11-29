<?php
/* @var $wp_post           wpPost           */
/* @var $cq_layout         string           */

  cq_page_title(
    $wp_post->getPostTitle(), null,
    array('class' => 'row-fluid header-bar')
  );
?>

<div class="spacer-bottom-15">
  <?php
    if ($wp_post_image = $wp_post->getPostThumbnail('original'))
    {
      echo cq_image_tag($wp_post_image, array('alt' => $wp_post->getPostTitle()));
    }
  ?>
</div>

<p class="truncate js-hide">
  <?= nl2br($wp_post->getPostContent()); ?>
</p>

<?php if ($cq_layout == 'grid'): ?>
<br>
<div class="row" style="margin-left: -12px;">
  <?php
    include_component(
      'misc', 'wordPressFeaturedItems',
      array('id' => $wp_post->getId())
    );
  ?>
<?php // div not closed intentionally because of pagination ?>
<?php elseif ($cq_layout == 'pinterest'): ?>
<div id="collectibles-holder" class="row thumbnails" style="margin-top: 10px;">
  <?php
    include_component(
      'misc', 'wordPressFeaturedItems',
      array('id' => $wp_post->getId())
    );
  ?>
<?php // div not closed intentionally because of pagination ?>
<?php endif; ?>

<script>
$(document).ready(function ()
{
  $('.truncate').expander({
    slicePoint: <?= strlen($wp_post->getPostExcerpt()) ?: 500; ?>,
    widow: 2,
    expandEffect: 'show',
    expandText: 'Read More',
    expandPrefix: '',
    userCollapseText: '[^]',
    onSlice: function() { $(this).show(); }
  })
  .show();

  var $container = $('#collectibles');

  $container.imagesLoaded(function()
  {
    $container.masonry(
      {
      <?php if ($cq_layout == 'pinterest'): ?>
        itemSelector : '.brick, .span4',
        columnWidth : 220, gutterWidth: 18
        <?php else: ?>
        itemSelector : '.span3, .span6',
        columnWidth : 140, gutterWidth: 15
        <?php endif; ?>
      });
  });
});
</script>
