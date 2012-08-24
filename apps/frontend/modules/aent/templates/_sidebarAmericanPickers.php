<div class="banner-sidebar-top">
  <?php cq_dart_slot('300x250', 'collections', 'americanpickers', 'sidebar') ?>
</div>

<?php /*
<div class="banner-sidebar-promo-300-90">
  <a href="<?= url_for('@aetn_storage_wars'); ?>" title="Check out items seen on Storage Wars">
    <img src="/images/headlines/storage-wars-banner.jpg" alt="Check out items seen on Storage Wars">
    <span>
      Check out items seen on Storage Wars
    </span>
  </a>
</div>
*/ ?>

<div class="banner-sidebar-promo-300-90">
  <a href="<?= url_for('@aetn_pawn_stars', true); ?>" title="Check out items seen on Pawn Stars">
    <img src="/images/headlines/pawn-stars-banner.jpg" alt="Check out items seen on Pawn Stars">
<?php /*
    <span>
      Check out items seen on Pawn Stars
    </span>
 */ ?>
  </a>
</div>

<?php
  include_component(
    '_sidebar', 'widgetMagnifyVideos',
    array('playlist' => 'American Pickers')
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetMoreHistory'
  );
?>
