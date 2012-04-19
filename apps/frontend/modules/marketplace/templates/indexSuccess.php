<?php cq_page_title('Market') ?>

<br/>
<div class="row-fluid" id="marketplace-spotlight">
  <h2 class="Chivo webfont spotlight-title">
    Spotlight on items from the Civil War
  </h2>
  <?php foreach ($spotlight as $i => $collectible_for_sale): ?>
  <div class="span4" style="width: 31%; <?= ($i == 0) ?: 'margin-left: 10px;'; ?>">
    <div class="thumbnail" style="background: white; border: 1px solid #C8BEB2;">
      <?= ice_image_tag_placeholder('260x260', array(), 1) ?>
      <h4 style="margin: 5px auto;"><?= link_to_collectible($collectible_for_sale->getCollectible(), 'text', array('class' => 'target')); ?></h4>
      <p><?= $collectible_for_sale->getCollectible()->getDescription('stripped', 255); ?></p>
      <div style="float: right; color: #cc0000; font-weight: bold; font-size: 130%; margin: 5px;">
        <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>
      </div>
      <br/><br/>
    </div>
  </div>
  <?php endforeach; ?>
  <div class="span12">&nbsp;</div>
</div>

<br/>
<div class="row" style="text-align: center;">
  <?= link_to(image_tag('banners/040412_show_and_sell_red.gif'), '@collector_signup'); ?>
</div>

<? cq_section_title('Discover more items for sale', link_to('see the marketplace', '@marketplace', array('class' => 'text-v-middle link-align'))); ?>

<div id="sort-search-box">
  <div class="input-append">
    <div class="btn-group open">
      <div class="append-left-gray">Sort By <strong>All Prices</strong></div>
      <a href="#" data-toggle="dropdown" class="btn gray-button dropdown-toggle"><span class="caret"></span></a>
      <ul class="dropdown-menu">
        <li><a href="#">Sort By <strong>Under $100</strong></a></li>
        <li><a href="#">Sort By <strong>$100 - $250</strong></a></li>
        <li><a href="#">Sort By <strong>Over $250</strong></a></li>
        <li><a href="#">Sort By <strong>Most Recent</strong></a></li>
      </ul>
    </div>
    <input type="text" size="16" id="appendedPrependedInput" class="input-sort-by"><button type="button" class="btn gray-button"><strong>Search</strong></button>
  </div>
</div>

<div class="row">
  <div id="collectibles" class="row-content">
  <?php
    /** @var $collectible_for_sale CollectibleForSale */
    foreach ($collectibles as $i => $collectible_for_sale)
    {
      echo '<div class="span4">';
      // Show the collectible (in grid, list or hybrid view)
      include_partial(
        'collection/collectible_for_sale_grid_view',
        array(
          'collectible_for_sale' => $collectible_for_sale,
          'culture' => (string) $sf_user->getCulture(),
          'i' => (int) $i
        )
      );
      echo '</div>';
    }
  ?>
  </div>
</div>

<script>
  $(document).ready(function()
  {
    $("#marketplace-spotlight a.target").bigTarget({
      hoverClass: 'over',
      clickZone : 'div:eq(0)'
    });
  });
</script>
