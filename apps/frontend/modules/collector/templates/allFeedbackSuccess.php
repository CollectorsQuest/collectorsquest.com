<?php
/**
 * @var $sf_user cqFrontendUser
 * @var $collector Collector
 * @var $profile CollectorProfile
 * @var $shopping_order_feedback ShoppingOrderFeedback
 */
?>

<div class="row-fluid header-bar">
  <div class="span9">
    <h1 class="Chivo webfont">
      <?= $collector->getDisplayName(); ?>'s Feedback
    </h1>
  </div>
  <div class="span3 text-right">
    <?= $sf_user->isOwnerOf($collector)
    ? link_to('Edit Your Profile →', '@mycq_profile')
    : '<a href="'.url_for_collector($collector).'">Profile →</a>'; ?>
  </div>
</div>

<div class="blue-actions-panel">
  <div class="row-fluid">
    <div class="span5">
      <div class="control-group pull-left">
        <div class="btn-filter-all btn-group">
          <?= link_to(
            'As a seller',
            '@collector_all_feedback_by_slug?slug=' . $collector->getSlug() . '&id=' . $collector->getId(),
            array('class' => 'btn btn-mini btn-filter ' . (!$filter_by ? 'active' : '') ));
          ?>
          <?= link_to(
            'Left for others',
            '@collector_all_feedback_by_slug?filter=others&slug=' . $collector->getSlug()
              . '&id='.$collector->getId(),
            array('class' => 'btn btn-mini btn-filter ' . ('others' == $filter_by ? 'active' : '') ));
          ?>
        </div>
      </div>
    </div>
    <div class="span6 pull-right" style="padding-top: 2px;">
      <span class="label label rating_negative pull-right spacer-left">
        <?= $collector->getFeedbackCount(ShoppingOrderFeedbackPeer::RATING_NEGATIVE) ?> negative
      </span>
      <span class="label label rating_neutral pull-right spacer-left">
        <?= $collector->getFeedbackCount(ShoppingOrderFeedbackPeer::RATING_NEUTRAL) ?> neutral
      </span>
      <span class="label rating_positive pull-right">
        <?= $collector->getFeedbackCount(ShoppingOrderFeedbackPeer::RATING_POSITIVE) ?> positive
      </span>
    </div>
  </div>
</div>

<table class="table ">
    <tbody>
    <?php if (!$pager->isEmpty()): foreach ($pager->getResults() as $shopping_order_feedback): ?>
    <tr>
        <td>
          <span class="label rating_<?= strtolower($shopping_order_feedback->getRating()) ?>">
            <?= $shopping_order_feedback->getRating() ?>
          </span>
        </td>
        <td style="max-width: 290px;"><?= $shopping_order_feedback->getComment() ?></td>
        <td><?= $shopping_order_feedback->getUpdatedAt('M d, Y') ?></td>
        <td>
            <?php
            echo link_to_collectible($shopping_order_feedback->getCollectible(), 'image', array(
                'image_tag' => array('width' => 50, 'height' => 50)
            ));
            ?>
      </td>
      <td>
            <?= link_to_collectible($shopping_order_feedback->getCollectible(), 'text', array('class' => 'target')); ?>
            <br />by <?= link_to_collector($shopping_order_feedback->getCollectible()->getCollector(), 'text'); ?>
        </td>
    </tr>
        <?php endforeach ?>
        <?php else: ?>
          <tr>
              <td colspan="5">Sorry no feedback Yet</td>
          </tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="row-fluid text-center">
<?php
  include_component(
    'global', 'pagination',
    array(
      'pager' => $pager,
      'options' => array(
        'id' => '_rating-pagination',
        'show_all' => false
      )
    )
  );
?>
</div>

