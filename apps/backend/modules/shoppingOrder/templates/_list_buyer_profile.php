<?php
/** @var $ShoppingOrder ShoppingOrder */
use_helper('cqImages');
$collector = $ShoppingOrder->getCollectorRelatedByCollectorId();

if ($collector)
{ ?>
  <a href="javascript:void(0)" rel="popover" data-original-title='
    <?= link_to($collector->getDisplayName(), 'collector_edit', $collector); ?>
    <button class="close" type="button">×</button>'
    data-content='
     <div class="row-fluid">
      <div class="span2">
       <?= image_tag_collector($collector, '100x100'); ?>
      </div>
      <div class="span8">
      <?= sprintf(
         '%s %s collector',
         in_array(
           strtolower(substr($collector->getCollectorType(), 0, 1)), array('a', 'e', 'i', 'o')
         ) ? 'An' : 'A',
         '<strong>'. $collector->getCollectorType() .'</strong>'
       );
       ?><br />
          <?php if ($country_iso3166 = $collector->getProfile()->getCountryIso3166())
          {
           echo 'From '.
             ($country_iso3166 == 'US') ? 'the United States' : $collector->getProfile()->getCountryName();
          } ?>
         <?= mail_to($collector->getEmail(), $collector->getEmail()) ?><br />
       </div>
     </div>
     '><i class="icon-user"></i>
  </a>
<?php
}
