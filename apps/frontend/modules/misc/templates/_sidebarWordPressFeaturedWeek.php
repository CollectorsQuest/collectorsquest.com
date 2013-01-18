<?php
/* @var $featured_weeks wpPost[] */
/* @var $wp_post_id integer */
?>

<div class="banner-sidebar-top">
  <?php cq_dart_slot('300x250', 'collections', 'collectibles', 'sidebar') ?>
</div>

<?php
/*$link = link_to(
  'See all &raquo;', '@wordpress_featured_weeks',
  array('class' => 'text-v-middle link-align')
);*/

cq_sidebar_title('Featured Themes');
?>

<div class="onecolumn cf">
  <ul>
    <?php foreach ($featured_weeks as $featured_week): ?>
      <?php if ($featured_week->getId() != $wp_post_id): ?>
        <li>
          <?php
            echo link_to(
              $featured_week->getPostTitle(),
              array('sf_route' => 'wordpress_featured_week', 'sf_subject' => $featured_week)
            );
          ?>
        </li>
      <?php endif; ?>
    <?php endforeach; ?>
  </ul>
</div>
