<?php $slots = $sf_request->getAttribute('slots', array(), 'cq/view/ads'); ?>

<script type="text/javascript" src="http://partner.googleadservices.com/gampad/google_service.js"></script>
<script type="text/javascript">
  GS_googleAddAdSenseService("ca-pub-5542261365602638");
  GS_googleEnableAllServices();
</script>
<script type="text/javascript">
<?php
  foreach ($slots as $slot)
  {
    echo sprintf('  GA_googleAddSlot("ca-pub-5542261365602638", "%s");'."\n", $slot);
  }
?>
</script>
<script type="text/javascript">
  GA_googleFetchAds();
</script>

<?php

// Deal with ads only in production
if (SF_ENV != 'prod' || empty($slots)) return;

foreach ($slots as $slot)
{
  echo '<div id="ad_slot_'. $slot .'_hidden" class="ad_slot" style="position: absolute; top: -1000px; left: -1000px;">';
  echo javascript_tag(sprintf('GA_googleFillSlot("%s");', $slot));
  echo '</div>';
}
