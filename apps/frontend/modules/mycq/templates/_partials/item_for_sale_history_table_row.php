<?php /* @var $collectible_for_sale CollectibleForSale */ ?>

<td>
    <?php
      echo link_to(
        image_tag_collectible(
          $collectible_for_sale->getCollectible(), '75x75',
          array('width' => 75, 'height' => 75)
        ),
        'mycq_collectible_by_slug',
        array('sf_subject' => $collectible_for_sale->getCollectible(), 'return_to' => 'market'),
        array('target' => '_blank', 'class' => 'thumb pull-left')
      );
    ?>
    <div class="pull-left">
      <span class="title">
        <?php
          echo link_to(
            $collectible_for_sale->getCollectible(), 'mycq_collectible_by_slug',
            array('sf_subject' => $collectible_for_sale->getCollectible(), 'return_to' => 'market')
          );
        ?>
      </span>
      <p class="description">
        <?= Utf8::truncateHtmlKeepWordsWhole($collectible_for_sale->getCollectible()->getDescription('stripped'), 100); ?>
      </p>
      <span class="price">
        <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>
      </span>
    </div>
  </div>
</td>
<td>
  <?= $collectible_for_sale->isForSale() ? $collectible_for_sale->getExpiryDate($format = 'F jS, Y') : '-'; ?>
</td>
<td class="status">
  <?= ucfirst($collectible_for_sale->getStatus()); ?>
</td>
<td>
  <?php switch ($collectible_for_sale->getStatus()):
    case CollectibleForSalePeer::STATUS_SOLD:
    case CollectibleForSalePeer::STATUS_EXPIRED: ?>
      <?php if (!$collectible_for_sale->getSeller()->hasPackageCredits()) : ?>
        <a href="<?php echo url_for('@seller_packages'); ?>" class="btn btn-mini">
          <i class="icon-plus"></i>&nbsp;Buy listings
        </a>
      <?php else: ?>
      <a data-id="<?= $collectible_for_sale->getCollectible()->getId(); ?>"
         class="collectible-action btn btn-mini"
         data-action="relist"
         data-confirm="Are you sure you sure you want to re-list this item?">
        <i class="icon-undo"></i>&nbsp;Re-list
      </a>
      <?php endif; ?>
      <?php break; ?>
    <?php case CollectibleForSalePeer::STATUS_ACTIVE: ?>
      <a data-id="<?= $collectible_for_sale->getCollectible()->getId(); ?>"
         class="collectible-action btn btn-mini"
         data-action="deactivate"
         data-confirm="Are you sure you sure you want to deactivate this item?">
        <i class="icon-minus-sign"></i>&nbsp;Deactivate
      </a>
      <?php break; ?>
    <?php case CollectibleForSalePeer::STATUS_INACTIVE: ?>
      <a data-id="<?= $collectible_for_sale->getCollectible()->getId(); ?>"
         class="collectible-action btn btn-mini"
         data-action="activate"
         data-confirm="Are you sure you sure you want to activate this item?">
        <i class="icon-plus-sign"></i>&nbsp;Activate
      </a>
      <?php break; ?>
  <?php endswitch; ?>
</td>
