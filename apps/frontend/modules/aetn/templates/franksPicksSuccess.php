<?php

echo image_tag('headlines/2012-0883_FP_Shop_Home_939x180_FIN.jpg',
  array(
    'alt' => 'One-of-a-kind finds picked fresh from Frank the host of American Pickers',
    'size' => '939x180'
  )
);

echo '<br/><br/>';
echo $collection->getDescription();
echo '<br/><br/>';

cq_page_title("Frank's Picks");
?>

<div id="collectibles-holder" class="row thumbnails" style="margin-top: 10px;">
  <?php include_component('aetn', 'franksPicksCollectiblesForSale'); ?>
</div>
