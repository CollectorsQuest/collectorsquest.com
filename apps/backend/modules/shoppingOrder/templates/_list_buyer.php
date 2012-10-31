<?php

/** @var $ShoppingOrder ShoppingOrder */
if ($ShoppingOrder->getBuyerEmail() || $ShoppingOrder->getShippingFullName())
{
  $full_name = $ShoppingOrder->getShippingFullName();
  $email = $ShoppingOrder->getBuyerEmail();
}
else if ($collector = CollectorQuery::create()->findOneById($ShoppingOrder->getCollectorId()))
{
  $full_name = $collector->getDisplayName();
  $email = $collector->getEmail();
}
else
{
  echo '<center>n/a</center>';
  return;
}

echo $full_name;

if ($ShoppingOrder->getNoteToSeller())
{
  ?>
<a href="javascript:void(0)" rel="clickover" data-original-title="Notes to seller" data-placement="top"
   data-content="<?= $ShoppingOrder->getNoteToSeller() ?>"> <i class="icon-info-sign"></i>
</a>
<?php
}
echo "<br />\n".mail_to($email, $email);
