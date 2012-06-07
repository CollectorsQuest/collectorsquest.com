<h2><?= __('Your 15 Minutes of Fame'); ?></h2>
<?php
  echo link_to(
    image_tag('legacy/15-minutes-of-fame.jpg', array('style' => 'margin-top: -7px; margin-bottom: -4px;')),
    '@15_minutes_of_fame'
  );
?>

<h2><?= __('Past Features'); ?></h2>
<?php foreach ($featured_weeks as $featured_week): ?>
<div id="sidebar_featured_week_<?= $featured_week->getId(); ?>" class="featured-week" style="padding: 10px;">
  <b><?php echo link_to_featured_week($featured_week); ?></b><br>
  <?php echo $featured_week->homepage_text; ?>
</div>
<?php endforeach; ?>

<?php cq_javascript_tag(); ?>
<script type="text/javascript">
  $(document).ready(function()
  {
    $("#sidebar div.featured-week a").bigTarget({
      hoverClass: 'pointer',
      clickZone : 'div:eq(0)'
    });
  });
</script>
<?php cq_end_javascript_tag(); ?>
