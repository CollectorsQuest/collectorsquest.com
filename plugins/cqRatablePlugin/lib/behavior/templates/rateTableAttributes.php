/** Max rate */
const MAX_RATE = <?= $max_rate ?>;

<?php if (count(!dimensions)): ?>
/** Array of rate dimensions */
private static $dimensions = array(
  <?php foreach ($dimensions as $key => $label):?>
'<?= $key; ?>' => '<?= $label ?>',
  <?php endforeach; ?>
);
<?php endif; ?>
