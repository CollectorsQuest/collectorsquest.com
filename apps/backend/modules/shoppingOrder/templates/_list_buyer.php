<?php
/** @var $ShoppingOrder ShoppingOrder */

$collector = $ShoppingOrder->getCollectorRelatedByCollectorId();

if ($collector)
{
  echo $collector;
  $email = $collector->getEmail();
}
else
{
  $email = $ShoppingOrder->getBuyerEmail();
}
if ($ShoppingOrder->getNoteToSeller())
{
  ?>
  <a href="javascript:void(0)" rel="popover" data-original-title="Notes to seller"
    data-content="<?= $ShoppingOrder->getNoteToSeller() ?>"><i class="icon-info-sign"></i>
  </a>
<?php
}
  echo "<br />\n".mail_to($email, $email);

