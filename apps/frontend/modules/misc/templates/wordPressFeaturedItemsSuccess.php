<?php
/* @var $wp_post           wpPost           */
/* @var $cq_layout         string           */

  cq_page_title(
    $wp_post->getPostTitle() .
      '<div id="social-sharing" class="pull-right share" style="height: 20px;">' .
      get_partial(
        'global/addthis',
        array(
          'providers' => array('google+', 'facebook'),
          'url' => $sf_request->getUri(),
        )
      ) .
      '</div>',
    null,
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

<div class="blue-actions-panel spacer-20">
  <div class="social-sharing pull-right share">
    <?php
      include_partial(
        'global/addthis',
        array(
          'image' => $wp_post_image ? cq_image_src($wp_post_image) : '',
          'url' => $sf_request->getUri(),
        )
      );
    ?>
  </div>
</div>

<script>
$(document).ready(function ()
{
  $('.truncate').expander({
    slicePoint: <?= strlen($wp_post->getPostExcerpt()) ?: 500; ?>,
    widow: 2,
    expandEffect: 'show',
    expandText: 'Read More',
    expandPrefix: '',
    userCollapseText: '',
    onSlice: function() { $(this).show(); }
  })
  .show();
});
</script>
