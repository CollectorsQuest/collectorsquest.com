<?php cq_ad_slot('300x250', 300, 250) ?>

<?php
cq_sidebar_title(
  'Popular Categories',
  link_to('See all &raquo;', '@content_categories', array('class' => 'text-v-middle link-align'))
);
?>

<div class="twocolumn cf">
  <ul>
    <li><?= link_to('American Pickers', '@aetn_american_pickers') ?></li>
    <li><?= link_to('Pawn Stars', '@aetn_pawn_stars') ?></li>
    <?php
      /** @var $categories CollectionCategory[] */
      foreach ($categories as $i => $category)
      {
        echo '<li>', ($category) ? link_to_content_category($category, 'text') : '', '</li>';
      }
    ?>
  </ul>
</div>

<?php include_component('_sidebar', 'widgetMagnifyVideos'); ?>
