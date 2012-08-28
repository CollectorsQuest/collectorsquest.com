<?php
/**
 * @var $form CollectibleForSaleBuyForm
 * @var $collectible Collectible
 * @var $collectible_for_sale CollectibleForSale
 * @var $height stdClass
 */
  $height = new stdClass;
  $height->value = sfContext::getInstance()->getUser()->getAttribute('height_main_div');
?>

<?php
  include_component(
    '_sidebar', 'widgetManageCollectible',
    array('collectible' => $collectible, 'height' => &$height)
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetCollectibleBuy',
    array('collectible' => $collectible, 'height' => &$height)
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetCollector',
    array(
      'collector' => $collectible->getCollector(),
      'collectible' => $collectible,
      'limit' => 0, 'message' => true, 'height' => &$height
    )
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetCollectionCollectibles',
    array('collectible' => $collectible, 'height' => &$height)
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetCollectiblesForSale',
    array(
      'collectible' => $collectible, 'limit' => 3,
      'fallback' => 'random', 'height' => &$height
    )
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetTags',
    array('collectible' => $collectible, 'height' => &$height)
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetCollections',
    array('collectible' => $collectible, 'fallback' => 'random',
      'height' => &$height
    )
  );
