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

<?php if ($sf_user->isAuthenticated()): ?>
  function woopraReady(tracker)
  {
    tracker.setDomain('<?= sfConfig::get('app_domain_name'); ?>');
    tracker.setIdleTimeout(300000);
    tracker.addVisitorProperty('name', '<?= $sf_user->getCollector()->getDisplayName(); ?>');
    tracker.addVisitorProperty('avatar', '<?= src_tag_collector($sf_user->getCollector(), '100x100'); ?>');
    tracker.track();

    return false;
  }

  Modernizr.load({
    load: '//static.woopra.com/js/woopra.js'
  });
<?php endif; ?>
</script>
