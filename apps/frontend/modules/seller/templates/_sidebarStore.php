<?php
/**
 * @var $collector                  Collector
 * @var $collections                CollectorCollection[]
 * @var $collection_id              integer
 * @var $num_views                  integer
 * @var $profile                    CollectorProfile
 * @var $store_shipping             string
 * @var $store_refunds              string
 * @var $store_return_policy        string
 * @var $store_additional_policies  string
 * @var $sf_user                    cqFrontendUser
 * @var $sf_request                 cqWebRequest
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

<div class="blue-actions-panel spacer-bottom">
  <div class="row-fluid">
    <div class="pull-left">
      <ul>
        <li>
          <?php
            echo format_number_choice(
              '[0] no views yet|[1] 1 View|(1,+Inf] %1% Views',
              array('%1%' => number_format($num_views)), $num_views
            );
          ?>
        </li>
      </ul>
    </div>
    <?php if (!$sf_request->isMobileBrowser()): ?>
    <div id="social-sharing" class="pull-right share">
      <?php
        include_partial(
          'global/addthis',
          array(
            'providers' => array('email', 'google+', 'facebook'),
            'image' => src_tag_collector($collector, 'original'),
            'url' => url_for('seller_store', $collector, true)
          )
        );
      ?>
    </div>
    <?php endif; ?>
  </div>
</div>


<?php
  if (!$sf_user->isOwnerOf($collector))
  {
    include_component(
      '_sidebar', 'widgetCollector',
      array(
        'collector' => $collector,
        'limit' => 0, 'message' => true, 'message_only' => true,
        'title' => 'Contact the Seller',
      )
    );
  }
?>

<?php
  include_partial(
    'collector/store_policy',
    array(
      'store_shipping' => $store_shipping, 'store_refunds' => $store_refunds,
      'store_return_policy' => $store_return_policy,
      'store_additional_policies' => $store_additional_policies,
      'collector' => $collector
    )
  )
?>
