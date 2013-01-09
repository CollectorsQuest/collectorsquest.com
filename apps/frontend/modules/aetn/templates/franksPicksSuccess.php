<?php
/* @var $sf_request cqWebRequest */
/* @var $collection Collection */

  if ($sf_request->isMobileLayout())
  {
    echo image_tag('headlines/2012-0883_FP_Shop_Home_620x180_FIN.jpg',
      array(
        'alt' => 'One-of-a-kind finds picked fresh from Frank the host of American Pickers',
        'size' => '620x180', 'style' => 'margin-left: 10px'
      )
    );
  }
  else
  {
    echo image_tag('headlines/2012-0883_FP_Shop_Home_939x180_FIN.jpg',
      array(
        'alt' => 'One-of-a-kind finds picked fresh from Frank the host of American Pickers',
        'size' => '939x180'
      )
    );
  }

?>

<br/><br/>

<div class="mobile-text-padding">
  <?= $collection->getDescription(); ?>
</div>

<br/><br/>

<?php cq_page_title("Frank's Picks"); ?>

<div id="collectibles-holder" class="row thumbnails" style="margin-top: 10px;">
  <?php
    if ($sf_request->isMobileLayout())
    {
      include_component('aetn', 'franksPicksCollectiblesForSaleMobile');
    }
    else
    {
      include_component('aetn', 'franksPicksCollectiblesForSale');
    }
  ?>
</div>
