<div class="banner-sidebar-top">
  <?php cq_dart_slot('300x250', 'collections', 'storagewars', 'sidebar') ?>
</div>

<div class="banner-sidebar-promo-300-90">
  <a href="<?= url_for('@aetn_pawn_stars', true); ?>" title="Check out items seen on Pawn Stars">
    <img src="/images/banners/pawn-stars-banner.jpg" alt="Check out items seen on Pawn Stars">
<?php /*
    <span>
      Check out items seen on Pawn Stars
    </span>
*/ ?>
  </a>
</div>
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

<?php include_component('_sidebar', 'widgetMagnifyVideos'); ?>

<?php
  include_component(
    '_sidebar', 'widgetMoreHistory'
  );
?>
