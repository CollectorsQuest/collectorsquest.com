<?php cq_page_title('Collections'); ?>

<br/>
<div id="weeks-promo-box">
  <div class="row-fluid">
    <div class="span8">
      <span class="weeks-promo-title Chivo webfont">Camera week: Strike a pose</span>
    </div>
    <div class="span4 text-right">
      <a href="#" class="link-align">See previous features &raquo;</a>
    </div>
  </div>
  <div class="row imageset">
    <div class="span-12">
      <ul class="thumbnails">
        <li class="span6">
          <a href="#">
            <?= ice_image_tag_flickholdr('370x302', array('i' => 4)) ?>
          </a>
          <span class="white-block">
           <p>Say cheese! This week we're featuring collectors who love to point and shoot for interesting cameras. They're ready for their close-up!</p>
          </span>
        </li>
        <li class="span3">
          <a href="#">
            <?= ice_image_tag_flickholdr('150x150'); ?>
          </a>
        </li>
        <li class="span3">
          <a href="#">
            <?= ice_image_tag_flickholdr('150x150'); ?>
          </a>
        </li>
        <li class="span3">
          <a href="#">
            <?= ice_image_tag_flickholdr('150x150'); ?>
          </a>
        </li>
        <li class="span3">
          <a href="#">
            <?= ice_image_tag_flickholdr('150x150'); ?>
          </a>
        </li>
        <li class="span3 dn">
          <a href="#">
            <?= ice_image_tag_flickholdr('150x150'); ?>
          </a>
        </li>
        <li class="span3 dn">
          <a href="#">
            <?= ice_image_tag_flickholdr('150x150'); ?>
          </a>
        </li>
      </ul>
    </div>
  </div>
  <button class="btn btn-small gray-button see-more-full">See more</button>
</div>

<? cq_section_title('Explore Collections') ?>

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
