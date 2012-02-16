<?php
/* @var $collector Collector */
/* @var $collectorEmail CollectorEmail */
include_partial('emails/header');
?>

<p style="margin-bottom: 10px; font-weight: bold;">
  <?php echo sprintf(__('Dear %s', array(), 'emails'), $collector->getDisplayName()); ?>,
</p>
<p>
  Your email has been changed to <strong><?php echo $collectorEmail->getEmail(); ?></strong> and needs verification
  <?php echo link_to('here', 'collector_verify_email', array('hash'=>$collectorEmail->getHash()), array('absolute' => true)); ?>.
</p>

<?php include_partial('emails/footer'); ?>
