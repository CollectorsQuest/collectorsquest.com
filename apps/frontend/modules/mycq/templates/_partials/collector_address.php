<?php
  /* @var $collector_address CollectorAddress */
?>
<div class="pull-left address-details">
  <!-- full name -->
  <strong><?= $collector_address->getFullName() ?></strong><br/>

  <!-- address line 1 -->
  <?= $collector_address->getAddressLine1() ?><br/>

  <!-- address line 2 (optional) -->
  <?php $al2 = $collector_address->getAddressLine2();
        echo $al2 ? $al2.'<br/>' : '' ?>

  <!-- city, state/prividence/region(optional), zip/postcode(optional) -->
  <?php echo $collector_address->getCity();
        $sr = $collector_address->getStateRegion();
        echo $sr ? ', '.$sr : '' ?>
  <?= ' '.$collector_address->getZipPostcode(); ?>
  <br/>

  <!-- country -->
  <?= $collector_address->getCountryName(); ?><br />

  <!-- phone -->
  Phone: <?= $collector_address->getPhone() ?><br/>
</div>