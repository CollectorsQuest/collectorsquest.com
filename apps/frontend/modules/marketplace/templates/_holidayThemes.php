<?php
/* @var $pager PropelModelPager */
/* @var $menu array */
/* @var $total integer */
?>

<div class="holiday-market-menu-wrapper">
  <div class="navbar-inner">
    <div class="centering">
      <ul class="nav">
      <?php foreach ($menu as $i => $item): ?>
        <li <?= $item['active'] ? 'class="active"' : null; ?>>
          <?php
            echo link_to(
              $item['name'], 'ajax_marketplace',
              array('section' => 'component', 'page' => 'holidayThemes', 't' => $offset+$i, 'p' => 1),
              array('anchor' => 'holiday-market-body', 'class' => 'ajax')
            );
          ?>
        </li>
      <?php endforeach; ?>
      </ul>
    </div>

    <?php if ($t > 0): ?>
    <span class="arrow-previous">
      <?php
        echo link_to(
          '&nbsp;', 'ajax_marketplace',
          array('section' => 'component', 'page' => 'holidayThemes', 't' => ($t-1 < 0 ? 0 : $t-1), 'p' => 1),
          array('anchor' => 'holiday-market-body', 'class' => 'ajax arrow-white-previous', 'title' => 'Previous Theme')
        );
      ?>
    </span>
    <?php endif; ?>

    <?php if ($total > $t+1): ?>
    <span class="arrow-next">
      <?php
        echo link_to(
          '&nbsp;', 'ajax_marketplace',
          array('section' => 'component', 'page' => 'holidayThemes', 't' => $t+1, 'p' => 1),
          array('anchor' => 'holiday-market-body', 'class' => 'ajax arrow-white-next', 'title' => 'Next Theme')
        );
      ?>
    </span>
    <?php endif; ?>
  </div>
</div>

<div class="collectibles-for-sale-3x-big-wrapper">
  <div class="row">
    <div class="row-content" style="margin-left: 24px;">

      <?php if ($pager->getPage() === 1): ?>
      <div id="collectible_for_sale_0_grid_view_square_big"
           class="span6 collectible_for_sale_grid_view_square_big fade-white link"
          style='background: url(<?= cq_image_src('frontend/misc/holiday-market/holiday-item-background.jpg') ?>) no-repeat;'>

        <div style="color: #fff; padding: 20px; font-size: 14px;">
          <?= $menu[($t-$offset < 0 ? 0 : $t-$offset)]['content']; ?>
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
              'url' => $url,'i' => $collectible_for_sale->getCollectibleId(),
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
          array('section' => 'component', 'page' => 'holidayThemes', 't' => $t, 'p' => $i),
          array('anchor' => 'holiday-market-body', 'class' => 'ajax bullet '. ($i === $pager->getPage() ? 'on' : 'off'))
        );
      }
    ?>
  </div>
</div>

<script>
  $(document).ready(function()
  {
    $('a.ajax').autoajax({
      onstart: function() {
        $('#holiday-market-body').showLoading();
      },
      oncomplete: function() {
        $('.fade-white').mosaic();
        $('#holiday-market-body').hideLoading();

        // TODO: how to check if the pagination link is clicked and not the nav one?
        if ($(this).hasClass('bullet'))
        {
          $.scrollTo('#holiday-market-body');
        }
      }
    });
  });
</script>
