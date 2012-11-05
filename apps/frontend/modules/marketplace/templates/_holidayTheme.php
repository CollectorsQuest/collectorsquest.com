<?php
/* @var $pager PropelModelPager */
?>

<div id="holiday-market-theme" class="collectibles-for-sale-3x-big-wrapper">
  <div class="row">
    <div class="row-content" style="margin-left: 24px;">

      <?php if ($pager->getPage() === 1): ?>
      <div id="collectible_for_sale_0_grid_view_square_big"
           class="span6 collectible_for_sale_grid_view_square_big fade-white link"
          style='background: url(<?= cq_image_src('frontend/misc/holiday-market/holiday-item-background.jpg') ?>) no-repeat;'>

        <div style="color: #fff; padding: 20px; font-size: 14px;">
          <?= $wp_post->getPostContent(); ?>
        </div>
      </div>
      <?php endif; ?>

      <?php
        foreach ($pager->getResults() as $collectible_for_sale)
        {
          // set the link to open modal dialog
          $url = url_for('ajax_marketplace',
            array(
              'section' => 'collectible',
              'page' => 'forSale',
              'id' => $collectible_for_sale->getCollectibleId()
            )
          );

          include_partial(
            'marketplace/collectible_for_sale_grid_view_square_big',
            array(
              'collectible_for_sale' => $collectible_for_sale,
              'url' => $url, 'i' => $collectible_for_sale->getCollectibleId(),
              'lazy_image' => false
            )
          );
        }
      ?>

    </div>
  </div>
  <div id="pages" class="spacer-bottom-15">
    <?php
      if ($pager->haveToPaginate())
      for ($i = 1; $i <= ($pager->getLastPage() <= 4 ? $pager->getLastPage() : 4); $i++)
      {
        echo link_to(
          '&nbsp;', 'ajax_marketplace',
          array('section' => 'component', 'page' => 'holidayTheme', 't' => $t, 'p' => $i),
          array('anchor' => 'holiday-market-theme', 'class' => 'ajax bullet '. ($i === $pager->getPage() ? 'on' : 'off'))
        );
      }
    ?>
  </div>
</div>

<script>
$(document).ready(function()
{
  $('a.ajax', '#holiday-market-theme').autoajax({
    onstart: function() {
      $('#holiday-market-theme').showLoading();
    },
    oncomplete: function() {
      $('.fade-white').mosaic();
      $('#holiday-market-theme').hideLoading();

      // TODO: how to check if the pagination link is clicked and not the nav one?
      if ($(this).hasClass('bullet'))
      {
        $.scrollTo('#holiday-market-body');
      }
    }
  });
});
</script>
