<?php
  /* @var $pager             PropelModelPager */
  /* @var $wp_post           wpPost           */
  /* @var $collectibles_4x4  array            */
  /* @var $collectibles_1x2  array            */
  /* @var $collectibles_2x1  array            */

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

<br/>
<div class="row" style="margin-left: -12px;">
  <div id="collectibles" class="row-content">
  <?php
    foreach ($pager->getResults() as $i => $collectible)
    {
      /* @var $collectible Collectible */
      $id = $collectible->getId();

      // which partial we want to show the Collectible with
      $partial = '';
      if (in_array($id, $collectibles_2x1))
      {
        $partial = 'wide';
      }
      else if (in_array($id, $collectibles_1x2))
      {
        $partial = 'tall';
      }
      else if (in_array($id, $collectibles_4x4))
      {
        $partial = 'square_big';
      }
      else
      {
        $partial = 'square_small';
      }

      include_partial(
        'collection/collectible_grid_view_' . $partial,
        array(
          'collectible' => $collectible, 'i' => (int) $i
        )
      );
    }
  ?>
  </div>
</div>

<div class="row-fluid text-center">
<?php
  include_component(
    'global', 'pagination', array('pager' => $pager)
  );
?>
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
    userCollapseText: '[^]',
    onSlice: function() { $(this).show(); }
  })
  .show();

  var $container = $('#collectibles');

  $container.imagesLoaded(function()
  {
    $container.masonry(
      {
        itemSelector : '.span3, .span6',
        columnWidth : 140, gutterWidth: 15
      });
  });
});
</script>
