<?php
/**
 * @var $collector      Collector
 * @var $collections    CollectorCollection[]
 * @var $collection_id  integer
 */
?>

<div class="well spacer-inner-8">
  <ul class="nav nav-list">

    <li class="nav-header">Store Sections:</li>
    <?php if (isset($collection_id)): ?>
      <li>
        <?php
          echo link_to (
            'View All', 'seller_store',
            array('sf_subject' => $collector)
          );
        ?>
      </li>
    <?php else: ?>
      <li class="active">
        <?php
        echo link_to (
          '<i class="icon-ok"></i> View All', 'seller_store',
          array('sf_subject' => $collector)
        );
        ?>
      </li>
    <?php endif; ?>
    <?php foreach ($collections as $collection): ?>
      <?php if ($collection->getId() != $collection_id): ?>
        <li>
          <?php
            echo link_to (
              $collection, 'seller_store',
              array('sf_subject' => $collector, 'collection_id' => $collection->getId()
              )
            );
          ?>
        </li>
      <?php else: ?>
        <li class="active">
          <?php
          echo link_to (
            '<i class="icon-ok"></i> ' . $collection, 'seller_store',
            array('sf_subject' => $collector, 'collection_id' => $collection->getId()
            )
          );
          ?>
        </li>
      <?php endif; ?>
    <?php endforeach; ?>
  </ul>
</div>

<?php
  include_partial(
    'collector/store_policy',
    array(
      'store_shipping' => $store_shipping, 'store_refunds' => $store_refunds,
      'store_return_policy' => $store_return_policy,
      'store_additional_policies' => $store_additional_policies
    )
  )
?>
