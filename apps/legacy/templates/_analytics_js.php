<?php
  /**
   * @var cqUser $sf_user
   * @var sfParameterHolder $sf_params
   */
?>

<script type="text/javascript">
//<![CDATA[
  (function()
  {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
//]]>
</script>

<script type="text/javascript" language="JavaScript">
//<![CDATA[
  var omniture = {
    s_account: "aetncollectorsquest",
    linkInternalFilters: ".collectorsquest.com",
    visitorMigrationServer: "",
    visitorMigrationServerSecure: ""
  };
//]]>
</script>
<script type="text/javascript" src="http://www.history.com/js/s_code.js"></script>
<script type="text/javascript">
//<![CDATA[
  <?php echo ($sf_params->get('purchase') == '1') ? 's.events = "event80";' : 's.events = "";'; ?>
  s.eVar11 = document.title;

  var s_code = s.t();
  if (s_code) document.write(s_code);
//]]>
</script>

<?php if ($sf_user->isAuthenticated()): ?>
<script type="text/javascript" src="http://static.woopra.com/js/woopra.v2.js"></script>
<script type="text/javascript">
//<![CDATA[
  woopraTracker.addVisitorProperty('name', '<?= $sf_user->getCollector()->getDisplayName(); ?>');
  woopraTracker.addVisitorProperty('avatar', '<?= src_tag_collector($sf_user->getCollector(), '100x100'); ?>');
  woopraTracker.track();
//]]>
</script>
<?php endif; ?>

<script type="text/javascript">
//<![CDATA[
  // The server load time for tracking in Google Analytics
  var server_load_time = <?= ceil(cqTimer::getInstance()->getElapsedTime() * 100); ?>;
//]]>
</script>
