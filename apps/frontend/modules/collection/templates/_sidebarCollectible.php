<?php
/**
 * @var $form CollectibleForSaleBuyForm
 * @var $collectible Collectible
 * @var $collectible_for_sale CollectibleForSale
 */
?>

<?php
  include_component(
    '_sidebar', 'widgetManageCollectible',
    array('collectible' => $collectible)
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetCollectibleBuy',
    array('collectible' => $collectible)
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetCollector',
    array(
      'collector' => $collectible->getCollector(),
      'collectible' => $collectible,
      'limit' => 0, 'message' => true
    )
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetCollectionCollectibles',
    array('collectible' => $collectible)
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetCollectiblesForSale',
    array(
      'collectible' => $collectible, 'limit' => 3,
      'fallback' => 'random'
    )
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetTags',
    array('collectible' => $collectible)
  );
?>

<?php
  include_component(
    '_sidebar', 'widgetCollections',
    array('collectible' => $collectible, 'fallback' => 'random')
  );
?>
