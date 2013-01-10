<?php
/* @var $featured_weeks wpPost[] */
?>

<div class="row thumbnails">
<?php
  foreach ($featured_weeks as $featured_week)
  {
    include_partial('misc/featuredWeek', array ('wp_post' => $featured_week));
  }
?>
</div>
