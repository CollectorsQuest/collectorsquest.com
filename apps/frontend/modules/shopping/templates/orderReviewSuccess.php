<script>
  if (window != top) {
    top.location.replace(document.location);
  }
</script>

<?php
  include_partial(
    'global/wizard_bar',
    array('steps' => array(1 => __('Shipping'), __('Payment'), __('Order Review')) , 'active' => 3)
  );
?>

<h2>Thank you for your order!</h2>

