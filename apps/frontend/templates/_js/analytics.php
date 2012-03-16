<?php
/**
 * @var cqFrontendUser $sf_user
 * @var sfParameterHolder $sf_params
 */
?>

<script>
  Modernizr.load({
    load: ('https:' == location.protocol ? '//ssl' : '//www') + '.google-analytics.com/ga.js'
  });

  window.omniture =
  {
    s_account: "aetncollectorsquest",
    linkInternalFilters: ".collectorsquest.com",
    visitorMigrationServer: "",
    visitorMigrationServerSecure: ""
  };

  Modernizr.load({
    load: 'http://www.history.com/js/s_code.js',
    complete : function ()
    {
      <?php // echo ($sf_params->get('purchase') == '1') ? 's.events = "event80";' : 's.events = "";'; ?>

      s.eVar11 = document.title;

      var s_code = s.t();
      if (s_code) document.write(s_code);
    }
  });

  <?php if ($sf_user->isAuthenticated()): ?>
  function woopraReady(tracker)
  {
    tracker.setDomain('<?= sfConfig::get('app_domain_name'); ?>');
    tracker.setIdleTimeout(300000);
    tracker.addVisitorProperty('name', '<?= $sf_user->getCollector()->getDisplayName(); ?>');
    tracker.addVisitorProperty('avatar', '<?= src_tag_collector($sf_user->getCollector(), '100x100'); ?>');
    tracker.track();

    alert('tracking');

    return false;
  }

  Modernizr.load({
    load: '//static.woopra.com/js/woopra.js'
  });
  <?php endif; ?>
</script>
