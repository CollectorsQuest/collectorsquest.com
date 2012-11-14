<?php
  /* @var $members Collector[] */
  /* @var $collector Collector */
?>

<?php cq_sidebar_title('Members'); ?>
<?php foreach ($members as $collector): ?>
  <?= cq_link_to(image_tag_collector($collector, '50x50'), route_for_collector($collector)); ?>
<?php endforeach; ?>
