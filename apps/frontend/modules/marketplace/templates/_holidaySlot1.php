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
        <li class="active">
          <a href="#">
            <span><strong>statue</strong>-esque</span>
          </a>
        </li>
        <li>
          <a href="#">
            up in <br><strong>smoke</strong>
          </a>
        </li>
        <li>
          <a href="#">
            <strong>Signs</strong><br> of the times
          </a>
        </li>
        <li>
          <a href="#">
            from the <br><strong>kitchen</strong>
          </a>
        </li>
        <li class="active">
          <a href="#">
            retro <br><strong>style</strong>
          </a>
        </li>
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
