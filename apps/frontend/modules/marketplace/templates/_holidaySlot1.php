<?php

echo cq_image_tag('frontend/mockups/20121001_CQ_Holiday_Wire_r3.pdf.jpg', array('width' => 979));

?>
<div class="row">
  <div class="row-content" style="margin-left: 24px;">
  <?php
    foreach ($collectibles_for_sale as $collectible_for_sale)
    {
      include_partial(
        'collection/collectible_grid_view_square_big',
        array(
          'collectible' => $collectible_for_sale->getCollectible(),
          'i' => $collectible_for_sale->getCollectibleId()
        )
      );
    }
  ?>
  </div>
</div>
