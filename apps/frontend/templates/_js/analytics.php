<?php
/**
 * @var cqFrontendUser $sf_user
 * @var sfParameterHolder $sf_params
 */
?>

<script>
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>

<!-- Start of Woopra Code -->
<script type="text/javascript">
  function woopraReady(tracker)
  {
    tracker.setDomain('<?= sfConfig::get('app_domain_name'); ?>');
    tracker.setIdleTimeout(300000);
    <?php if ($sf_user->isAuthenticated()): ?>
    tracker.addVisitorProperty('name', '<?= $sf_user->getCollector()->getDisplayName(); ?>');
    tracker.addVisitorProperty('avatar', '<?= src_tag_collector($sf_user->getCollector(), '100x100'); ?>');
    tracker.addVisitorProperty('email','<?= $sf_user->getCollector()->getEmail() ?>');
    <?php endif; ?>
    tracker.track();

    return false;
  }

  (function()
  {
    var wsc = document.createElement('script');
    wsc.src = document.location.protocol+'//static.woopra.com/js/woopra.js';
    wsc.type = 'text/javascript';
    wsc.async = true;
    var ssc = document.getElementsByTagName('script')[0];
    ssc.parentNode.insertBefore(wsc, ssc);
  })();
</script>
<!-- End of Woopra Code -->
