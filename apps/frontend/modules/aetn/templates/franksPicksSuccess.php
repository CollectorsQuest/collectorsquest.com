<?php

echo ice_image_tag_placeholder('939x180');
echo '<br/><br/>';

cq_page_title("Frank's Picks");

?>
<div id="collectibles-holder" class="row thumbnails" style="margin-top: 10px;">
  <?php include_component('aetn', 'franksPicksCollectiblesForSale'); ?>
</div>
