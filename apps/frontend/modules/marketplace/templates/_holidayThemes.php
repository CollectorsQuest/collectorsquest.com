<?php
/* @var $pager PropelModelPager */
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
    <?php
      foreach ($pager->getResults() as $collectible_for_sale)
      {
        include_partial(
          'marketplace/collectible_for_sale_grid_view_square_big',
          array(
            'collectible_for_sale' => $collectible_for_sale,
            'lazy_image' => false, 'i' => $collectible_for_sale->getCollectibleId()
          )
        );
      }
    ?>
    </div>
  </div>
  <div id="pages" class="spacer-bottom-15">
    <?php
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
      }
    });
  });
</script>
