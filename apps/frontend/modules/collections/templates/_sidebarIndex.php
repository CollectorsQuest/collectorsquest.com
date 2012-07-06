<?php cq_dart_slot('300x250', 'collections', 'landing', 'sidebar') ?>

<?php
  $link = link_to(
    'See all &raquo;',
    '@content_categories', array('class' => 'text-v-middle link-align')
  );
  $link = null;

  cq_sidebar_title('Popular Categories', $link);
?>

<div class="twocolumn cf">
  <ul>
    <li><?= link_to('American Pickers', '@aetn_american_pickers') ?></li>
    <li><?= link_to('Pawn Stars', '@aetn_pawn_stars') ?></li>
    <?php
      /** @var $categories ContentCategory[] */
      foreach ($categories as $i => $category)
      {
        echo '<li>', ($category) ? link_to_content_category($category, 'text') : '', '</li>';
      }
    ?>
  </ul>
</div>

<?php
  if (!$sf_user->isAuthenticated())
  {
    echo link_to(
      image_tag('banners/2012-06-24_CQGuidePromo_300x90.png', array('class' => 'spacer-top-20')),
      '@misc_guide_to_collecting'
    );
  }
?>

<?php include_component('_sidebar', 'widgetMagnifyVideos'); ?>
