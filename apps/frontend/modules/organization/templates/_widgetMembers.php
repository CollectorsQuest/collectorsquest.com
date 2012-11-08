<?php
  /* @var $members Collector[] */
  /* @var $collector Collector */
?>
<h3>Members: </h3>
<?php foreach ($members as $collector): ?>
  <?= link_to_collector($collector, 'image'); ?>
<?php endforeach; ?>
