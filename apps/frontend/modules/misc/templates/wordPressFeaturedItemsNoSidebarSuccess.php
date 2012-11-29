<?php
/* @var $pager             PropelModelPager */
/* @var $wp_post           wpPost           */
/* @var $collectibles_2x2  array            */
/* @var $collectibles_1x2  array            */
/* @var $collectibles_2x1  array            */
/* @var $layout            string           */
/* @var $infinite_scroll   boolean          */
/* @var $cq_layout         string           */
?>

<p class="truncate js-hide">
  <?= nl2br($wp_post->getPostContent()); ?>
</p>

<?php
  cq_page_title(
    $wp_post->getPostTitle(), null,
    array('class' => 'row-fluid header-bar')
  );
?>

<?php if ($cq_layout == 'grid'): ?>
<br>
<div class="row" style="margin-left: -12px;">
  <?php
    include_component(
      'misc', 'wordPressFeaturedItemsGrid',
      array(
        'collectibles_2x2' => $collectibles_2x2, 'collectibles_1x2' => $collectibles_1x2,
        'collectibles_2x1' => $collectibles_2x1, 'pager' => $pager, 'infinite_scroll' => $infinite_scroll
      )
    );
  ?>
</div>

<?php elseif ($cq_layout == 'pinterest'): ?>
<div id="collectibles-holder" class="row thumbnails" style="margin-top: 10px;">
  <?php
    include_component(
      'misc', 'wordPressFeaturedItemsPinterest',
      array('pager' => $pager, 'infinite_scroll' => $infinite_scroll)
    );
  ?>
</div>
<?php endif; ?>

<?php if ($infinite_scroll !== true): ?>
<div class="row-fluid text-center clear">
  <?php
    include_component(
    'global', 'pagination', array('pager' => $pager, 'page_param' => 'page')
    );
  ?>
</div>
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
  });
</script>
