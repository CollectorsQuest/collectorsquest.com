<div class="banner-sidebar-top">
  <?php cq_dart_slot('300x250', 'collections', 'pawnstars', 'sidebar') ?>
</div>

<?php /*
<div class="banner-sidebar-promo-300-90">
  <a href="<?= url_for('@aetn_storage_wars', true); ?>" title="Check out items seen on Storage Wars">
    <img src="/images/banners/storage-wars-banner.jpg" alt="">
    <span>
      Check out items seen on Storage Wars
    </span>
  </a>
</div>
*/ ?>

<div class="banner-sidebar-promo-300-90">
  <a href="<?= url_for('@aetn_american_pickers', true); ?>" title="Check out items seen on American Pickers">
    <img src="/images/banners/american-pickers-banner.jpg" alt="Check out items seen on American Pickers">
<?php /*
    <span>
      Check out items seen on American Pickers
    </span>
*/ ?>
  </a>
</div>

<?php
  include_component(
    '_sidebar', 'widgetMagnifyVideos',
    array('playlist' => 'Pawn Stars')
  );
?>

<div class="row-fluid sidebar-title">
  <div class="span11">
    <h3 class="Chivo webfont">
      More Pawn Stars
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

