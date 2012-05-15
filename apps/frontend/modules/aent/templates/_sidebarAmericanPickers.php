<div class="banner-sidebar-top">
  <?php cq_dart_slot('300x250', 'collections', 'americanpickers', 'sidebar') ?>
</div>

<?php /*
<div class="banner-sidebar-promo-300-90">
  <a href="<?= url_for('@aetn_storage_wars'); ?>" title="Check out items seen on Storage Wars">
    <img src="/images/banners/storage-wars-banner.jpg" alt="Check out items seen on Storage Wars">
    <span>
      Check out items seen on Storage Wars
    </span>
  </a>
</div>
*/ ?>

<div class="banner-sidebar-promo-300-90">
  <a href="<?= url_for('@aetn_pawn_stars', true); ?>" title="Check out items seen on Pawn Stars">
    <img src="/images/banners/pawn-stars-banner.jpg" alt="Check out items seen on Pawn Stars">
    <span>
      Check out items seen on Pawn Stars
    </span>
  </a>
</div>

<?php
  include_component(
    '_sidebar', 'widgetMagnifyVideos',
    array('playlist' => 'American Pickers')
  );
?>

<div class="row-fluid sidebar-title">
  <div class="span11">
    <h3 class="Chivo webfont">
      More American Pickers
    </h3>
  </div>
  <div class="span1 text-right">
    &nbsp;
  </div>
</div>

<div id="programming-notes">
  <div class="row-fluid">
    <div class="span3">
      <a href="#">
        <img src="http://placehold.it/62x46" alt="">
      </a>
    </div>
    <div class="span9 fix-height-text-block">
      <div class="content-container">
        <p>
          Monday, March 26, 2012  8/7c
        </p>
        <a href="#" title="Help Wanted">
          Help Wanted
        </a>
      </div>
    </div>
  </div>
  <div class="row-fluid">
    <div class="span3">
      <a href="#">
        <img src="http://placehold.it/62x46" alt="">
      </a>
    </div>
    <div class="span9 fix-height-text-block">
      <div class="content-container">
        <p>
          Monday, March 26, 2012  8/7c
        </p>
        <a href="#" title="Help Wanted">
          Help Wanted
        </a>
      </div>
    </div>
  </div>
  <div class="row-fluid">
    <div class="span3">
      <a href="#">
        <img src="http://placehold.it/62x46" alt="">
      </a>
    </div>
    <div class="span9 fix-height-text-block">
      <div class="content-container">
        <p>
          Monday, March 26, 2012  8/7c
        </p>
        <a href="#" title="Help Wanted">
          Help Wanted
        </a>
      </div>
    </div>
  </div>
</div>

