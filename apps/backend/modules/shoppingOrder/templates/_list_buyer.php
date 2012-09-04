<?php
/** @var $ShoppingOrder ShoppingOrder */

echo $ShoppingOrder->getShippingFullName();

if ($ShoppingOrder->getNoteToSeller())
{
  ?>
  <a href="javascript:void(0)" rel="popover" data-original-title="Notes to seller"
     data-content="<?= $ShoppingOrder->getNoteToSeller() ?>"> <i class="icon-info-sign"></i>
  </a>
  <?php
}
echo "<br />\n".mail_to($ShoppingOrder->getBuyerEmail(), $ShoppingOrder->getBuyerEmail());
