<?php
  // Deal with ads only in production
  if (SF_ENV != 'prod' || empty($slots)) return;
?>

<script src="//partner.googleadservices.com/gampad/google_service.js"></script>
<script>
  GS_googleAddAdSenseService("ca-pub-5542261365602638");
  GS_googleEnableAllServices();
  <?php
  /** @var $slots array */
  foreach ($slots as $slot)
  {
    echo sprintf('  GA_googleAddSlot("ca-pub-5542261365602638", "%s");'."\n", $slot);
  }
  ?>
  GA_googleFetchAds();
</script>

<?php
foreach ($slots as $slot)
{
  echo '<div id="ad_slot_'. $slot .'_hidden" class="ad_slot" style="position: absolute; top: -1000px; left: -1000px;">';
  echo javascript_tag(sprintf('GA_googleFillSlot("%s");', $slot));
  echo '</div>';
}
?>
