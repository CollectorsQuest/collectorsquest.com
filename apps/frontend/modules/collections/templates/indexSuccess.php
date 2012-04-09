<?php cq_page_title('Collections'); ?>

<?php /* <?php cq_section_title('Explore collections'); ?>*/?>

<div class="weeks-promo-box">
  <div class="row-fluid">
    <div class="span8">
      <span class="weeks-promo-title">Camera week: Strike a pose</span>
    </div>
    <div class="span4 text-right">
      <a href="#">See previous features &raquo;</a>
    </div>
  </div>

  <button class="btn btn-small gray-button see-more-full">See more</button>
</div>


<h2 class="section-title">Explore Collections</h2>

<div class="collection-search-box">
  <div class="input-append">
    <div class="btn-group open">
      <div class="append-left-gray">Sort By <strong>Collections</strong></div>
      <a href="#" data-toggle="dropdown" class="btn gray-button dropdown-toggle"><span class="caret"></span></a>
      <ul class="dropdown-menu">
        <li><a href="#">Sort By <strong>Collectibles</strong></a></li>
        <li><a href="#">Sort By <strong>Most Recent</strong></a></li>
        <li><a href="#">Sort By <strong>Most Wanted</strong></a></li>
      </ul>
    </div>
    <input type="text" size="16" id="appendedPrependedInput" class="sort-by-search"><button type="button" class="btn gray-button"><strong>Search</strong></button>
  </div>
</div>

<div class="row">
  <div id="collectibles" class="row-content">
    <?php
    /** @var $collections Collection[] */
    foreach ($collections as $i => $collection)
    {
      echo '<div class="span4" style="margin-bottom: 15px">';
      include_partial(
        'collection/collection_grid_view',
        array('collection' => $collection, 'i' => $i)
      );
      echo '</div>';
    }
    ?>
  </div>
</div>
<div class="see-more-under-image-set">
  <button class="btn btn-small gray-button see-more-full">See more</button>
</div>
