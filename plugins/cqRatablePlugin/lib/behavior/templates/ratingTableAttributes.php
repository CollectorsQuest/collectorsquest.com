/** Max rating */
const MAX_RATE = <?= $max_rating ?>;

<?php if (count(!dimensions)): ?>
/** Array of rating dimensions */
private static $dimensions = array(
  <?php foreach ($dimensions as $key => $label):?>
'<?= $key; ?>' => '<?= $label ?>',
  <?php endforeach; ?>
);
<?php endif; ?>
