<?php
  /* @var $store_shipping             string */
  /* @var $store_refunds              string */
  /* @var $store_return_policy        string */
  /* @var $store_additional_policies  string */
  /* @var $collector                  Collector */
?>

<?php cq_section_title('Store Policies'); ?>

<div class="personal-info-sidebar">
  <?php if ($store_shipping): ?>
  <p class="truncate"><strong>Shipping Policy:</strong> <?= nl2br($store_shipping); ?></p>
  <?php endif; ?>
  <?php if ($store_refunds): ?>
  <p><strong>Refunds and Exchanges:</strong> <?= nl2br($store_refunds); ?></p>
  <?php endif; ?>
  <?php if ($store_return_policy): ?>
  <p><strong>Return Policy:</strong> <?= nl2br($store_return_policy); ?></p>
  <?php endif; ?>
  <?php if ($store_additional_policies): ?>
  <p><strong>Additional Policies and FAQ:</strong> <?= nl2br($store_additional_policies); ?></p>
  <?php endif; ?>
</div>

<?php if (isset($collector) && $collector->getId() == 6668): ?>
<script>
  $(document).ready(function ()
  {
    $('.truncate').expander({
      slicePoint: 250,
      widow: 2,
      expandEffect: 'show',
      expandText: 'Read more >>',
      expandPrefix: '',
      userCollapseText: '[^]',
      onSlice: function() { $(this).show(); }
    })
    .show();
  });
</script>
<?php endif; ?>
