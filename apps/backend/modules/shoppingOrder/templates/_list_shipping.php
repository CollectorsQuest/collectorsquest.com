<?php
/** @var $ShoppingOrder ShoppingOrder */
?>
<?php if ($ShoppingOrder->getShippingCity())
{ ?>
  <a href="javascript:void(0)" rel="clickover" data-original-title="Shipping Details
  <button class='close' type='button'>×</button>" data-placement="top"
      data-content="

         <b><?= $ShoppingOrder->getShippingFullName(); ?></b><br /><br />
         <?= $ShoppingOrder->getShippingAddressLine1(); ?><br />
         <?= $ShoppingOrder->getShippingAddressLine2(); ?><br />
         <?= $ShoppingOrder->getShippingZipPostcode()
          .' '.$ShoppingOrder->getShippingCity()
          .' '.$ShoppingOrder->getShippingStateRegionName(); ?><br />
         <?= $ShoppingOrder->getShippingCountryName(); ?><br />

         <?php if ($ShoppingOrder->getShippingCarrier()): ?>
           <br /><b>Tracking Number:</b> <?= $ShoppingOrder->getShippingCarrier(); ?><br />
         <?php endif; ?>
         <?php if ($ShoppingOrder->getShippingTrackingNumber()): ?>
           <br /><b>Tracking Number:</b> <?= $ShoppingOrder->getShippingTrackingNumber(); ?><br />
         <?php endif; ?>
         ">
    <?= $ShoppingOrder->getShippingCity(); ?>
  </a>
<?php
}

