<?php
/* @var $sf_user     cqFrontendUser */
/* @var $sf_request  cqWebRequest */
/* @var $wp_post     wpPost */

?>

<?php if (cqGateKeeper::open('collections_redesign', 'page') && $wp_post instanceof wpPost): ?>
<div id="weeks-promo-box" style="padding: 0; background-color: transparent;">
<div class="row imageset">
  <div class="span-12" style="width: 300px;">
    <ul class="thumbnails" style="width: 300px;">
      <li class="span6" style="width: 300px;">
        <a href="<?= url_for('wordpress_featured_week', array('sf_subject' => $wp_post)); ?>">
          <?= image_tag_wp_post($wp_post, '300x300'); ?>
          <span class="white-block" style="width: 271px;">
            <?= $wp_post->getPostContent(); ?>
          </span>
        </a>
      </li>
    </ul>
  </div>
</div>
</div>
<?php else: ?>
  <?php cq_dart_slot('300x250', 'collections', 'landing', 'sidebar'); ?>
<?php endif; ?>

<?php
  if ($sf_request->isMobileLayout())
  {
    include_partial('aetn/partials/franksPicksPromo_620x67', array('class' => 'spacer-top-20'));
  }
  else
  {
    include_partial('aetn/partials/franksPicksPromo_300x90', array('class' => 'spacer-top-20'));
  }
?>

<?php include_component('_sidebar', 'widgetPopularCategories'); ?>

<?php
  if (!$sf_user->isAuthenticated())
  {
    if ($sf_request->isMobileLayout())
    {
      cq_ad_slot(
        cq_image_tag('headlines/2012-06-24_CQGuidePromo_635x111.png',
          array(
            'width' => '635', 'height' => '111', 'class' => 'spacer-top-20',
            'alt' => 'Quest Your Best: The Essential Guide to Collecting'
          )
        ),
        '@misc_guide_to_collecting'
      );
    }
    else
    {
      cq_ad_slot(
        cq_image_tag('headlines/2012-06-24_CQGuidePromo_300x90.png',
          array(
            'width' => '300', 'height' => '90', 'class' => 'spacer-top-20 mobile-optimized-300 center',
            'alt' => 'Quest Your Best: The Essential Guide to Collecting'
          )
        ),
        '@misc_guide_to_collecting'
      );
    }
  }
?>

<?php
/*  include_partial(
    'marketplace/partials/holidayMarketBuyPackagePromo_300x90',
    array('class' => 'spacer-top-20')
  );*/
?>

<?php
  if (cqGateKeeper::locked('collections_redesign', 'page'))
  {
    include_component('_sidebar', 'widgetMagnifyVideos');
  }
?>
