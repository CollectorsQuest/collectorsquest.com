<div id="HolidayMarketHeader">
  <!--
  <h1>Keep It<br>Classics</h1>
  <h2>for the holidays</h2>
  -->
</div>
<div class="holiday-market-menu-wrapper">
  <div class="navbar-inner">
    <div class="centering">
      <ul class="nav">
        <?php foreach ($menu as $i => $item): ?>
        <li <?= ($i === 0) ? 'class="active"' : null; ?>>
          <a href="#"><?= $item['name']; ?></a>
        </li>
         <?php endforeach; ?>
      </ul>
    </div>

    <span class="arrow-previous">
      <a href="#" class="arrow-white-previous" title="previous"></a>
    </span>
    <span class="arrow-next">
      <a href="#" class="arrow-white-next" title="next"></a>
    </span>
  </div>
</div>

<div class="collectibles-for-sale-3x-big-wrapper">
  <div class="row">
    <div class="row-content" style="margin-left: 24px;">
      <?php
      foreach ($collectibles_for_sale as $collectible_for_sale)
      {
        include_partial(
          'collection/collectible_grid_view_square_big',
          array(
            'collectible' => $collectible_for_sale->getCollectible(),
            'i' => $collectible_for_sale->getCollectibleId()
          )
        );
      }
      ?>
    </div>
  </div>
  <div id="pages">
    <a href="#" class="bullet on"></a>
    <a href="#" class="bullet off"></a>
    <a href="#" class="bullet off"></a>
    <a href="#" class="bullet off"></a>
  </div>
  <p class="inform spacer-bottom">More items in this theme</p>
</div>
