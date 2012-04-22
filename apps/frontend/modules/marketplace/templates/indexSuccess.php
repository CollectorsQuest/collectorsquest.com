<?php cq_page_title('Market') ?>

<div class="row-fluid" id="marketplace-spotlight">
  <h2 class="spotlight-title Chivo webfont">
    Spotlight on items from the Civil War
  </h2>
  <?php foreach ($spotlight as $i => $collectible_for_sale): ?>
  <div class="span4">
    <div class="thumbnail">
      <div class="spotlight-thumb">
        <?= ice_image_tag_placeholder('180x180', array(), 1) ?>
        <span>AFFORDABLE</span>
      </div>
      <div class="spotlight-text">
        <h4><?= link_to_collectible($collectible_for_sale->getCollectible(), 'text', array('class' => 'target')); ?></h4>
        <p><?= $collectible_for_sale->getCollectible()->getDescription('stripped', 120); ?></p>
      </div>
      <div class="spotlight-price">
        <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<br/>
<div class="banners-620">
  <?= link_to(image_tag('banners/040412_show_and_sell_red.gif'), '@collector_signup'); ?>
</div>

<? cq_section_title('Discover more items for sale', link_to('see the marketplace', '@marketplace', array('class' => 'text-v-middle link-align'))); ?>

<div id="sort-search-box">
  <div class="input-append">
    <form action="" method="post">
      <div class="btn-group open">
        <div class="append-left-gray">Sort By <strong>All Prices</strong></div>
        <a href="#" data-toggle="dropdown" class="btn gray-button dropdown-toggle">
          <span class="caret arrow-up"></span><br><span class="caret arrow-down"></span>
        </a>
        <ul class="dropdown-menu">
          <li><a href="#">Sort By <strong>Under $100</strong></a></li>
          <li><a href="#">Sort By <strong>$100 - $250</strong></a></li>
          <li><a href="#">Sort By <strong>Over $250</strong></a></li>
          <li><a href="#">Sort By <strong>Most Recent</strong></a></li>
        </ul>
      </div>
      <input type="text" size="16" id="appendedPrependedInput" class="input-sort-by"><button type="button" class="btn gray-button"><strong>Search</strong></button>
      </form>
  </div>
</div>

<div id="items-for-sale">
  <div class="row">
    <ul class="thumbnails">
      <li class="span3">
        <a class="thumbnail" href="#">
          <img src="http://placehold.it/131x128" alt="">
          <p>Transformer - Perfect...</p>
          <span>$212,000</span>
        </a>
      </li>
      <li class="span3">
        <a class="thumbnail" href="#">
          <img src="http://placehold.it/131x128" alt="">
          <p>Transformer - Perfect...</p>
          <span>$212,000</span>
        </a>
      </li>
      <li class="span3">
        <a class="thumbnail" href="#">
          <img src="http://placehold.it/131x128" alt="">
          <p>Transformer - Perfect...</p>
          <span>$212,000</span>
        </a>
      </li>
      <li class="span3">
        <a class="thumbnail" href="#">
          <img src="http://placehold.it/131x128" alt="">
          <p>Transformer - Perfect...</p>
          <span>$212,000</span>
        </a>
      </li>
      <li class="span3">
        <a class="thumbnail" href="#">
          <img src="http://placehold.it/131x128" alt="">
          <p>Transformer - Perfect...</p>
          <span>$212,000</span>
        </a>
      </li>
      <li class="span3">
        <a class="thumbnail" href="#">
          <img src="http://placehold.it/131x128" alt="">
          <p>Transformer - Perfect...</p>
          <span>$212,000</span>
        </a>
      </li>
      <li class="span3">
        <a class="thumbnail" href="#">
          <img src="http://placehold.it/131x128" alt="">
          <p>Transformer - Perfect...</p>
          <span>$212,000</span>
        </a>
      </li>
      <li class="span3">
        <a class="thumbnail" href="#">
          <img src="http://placehold.it/131x128" alt="">
          <p>Transformer - Perfect...</p>
          <span>$212,000</span>
        </a>
      </li>
    </ul>
  </div>
</div>

<?php /*
<div class="row">
  <div id="collectibles" class="row-content">
  <?php
    // @var $collectibles_for_sale CollectibleForSale[]
    foreach ($collectibles_for_sale as $i => $collectible_for_sale)
    {
      echo '<div class="span4">';
      // Show the collectible (in grid, list or hybrid view)
      include_partial(
        'collection/collectible_for_sale_grid_view',
        array('collectible_for_sale' => $collectible_for_sale, 'i' => (int) $i)
      );
      echo '</div>';
    }
  ?>
  </div>
</div>
*/?>

<script>
  $(document).ready(function()
  {
    $("#marketplace-spotlight a.target").bigTarget({
      hoverClass: 'over',
      clickZone : 'div:eq(0)'
    });
  });
</script>
