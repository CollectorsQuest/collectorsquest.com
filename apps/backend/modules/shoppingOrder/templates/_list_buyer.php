<?php

/** @var $ShoppingOrder ShoppingOrder */
if ($ShoppingOrder->getBuyerEmail() || $ShoppingOrder->getShippingFullName())
{
  echo $ShoppingOrder->getShippingFullName();

  if ($ShoppingOrder->getNoteToSeller())
  {
    ?>
    <a href="javascript:void(0)" rel="clickover" data-original-title="Notes to seller" data-placement="top"
       data-content="<?= $ShoppingOrder->getNoteToSeller() ?>"> <i class="icon-info-sign"></i>
    </a>
    <?php
  }
  echo "<br />\n".mail_to($ShoppingOrder->getBuyerEmail(), $ShoppingOrder->getBuyerEmail());
}
else
{
  include_partial('list_seller', array('ShoppingOrder' => $ShoppingOrder));
}
