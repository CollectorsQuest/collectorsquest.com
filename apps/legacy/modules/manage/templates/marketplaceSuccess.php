<style type="text/css">
  .ui-state-active, .ui-widget-content .ui-state-active {
    background: #fff;
  }
  .ui-tabs .ui-tabs-panel {
    padding: 30px 0 0 0;
  }
</style>

<div class="yui-g">
  <!--Content-->
  <table height="100%" width="974">
    <tr>
      <td style="width: 100%; min-width: 604px; padding: 10px; padding-bottom: 20px" valign="top">
        <div style="float: left; margin-top: 6px; margin-right: 5px;">
          <?php image_tag('black-arrow.png'); ?>
        </div>
        <div id="marketplace" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
          <ul class="ui-tabs ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
            <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#collectibles_selling"><?php echo __("Collectibles You're Selling") ?></a></li>
            <li class="ui-state-default ui-corner-top"><a href="#collectibles_sold"><?php echo __("Collectibles You've Sold") ?></a></li>
            <li class="ui-state-default ui-corner-top" style="float: right;"><a href="#collectibles_bought"><?php echo __("Collectibles You've Bought") ?></a></li>
            <li class="ui-state-default ui-corner-top" style="float: right;"><a href="#collectibles_buying"><?php echo __("Collectibles You're Buying") ?></a></li>
          </ul>
          <div id="collectibles_selling" class="ui-tabs-panel" style="padding-left: 13px;">
            <?php
            if (!empty($collectibles_for_sale)):
              foreach ($collectibles_for_sale as $collectible_for_sale):
                $collectible = $collectible_for_sale->getCollectible();
                ?>
                <div id="item_.<?= $collectible->getId() ?>" class="grid_item" style="margin-left: 10px;">
                  <div style="float: right;">
                    <?php
                    echo link_to(image_tag("/images/icons/accessories-text-editor.png", array('title' => 'edit item')), 'manage_collectible_by_slug', $collectible) . '&nbsp;';
                    echo link_to(image_tag("/images/icons/emblem-unreadable.png",
                      array('title' => 'remove', 'alt' => 'remove from market')),
                      "@collectible_remove?id=" . $collectible->getId(),
                      array("confirm" => "Are you sure you want to remove this item from your market?\n\nNOTE: This item will NOT be deleted from any of your collections!"
                      ));
                    ?>
                  </div>
                  <br clear="all">
                  <?php echo link_to_collectible($collectible, 'image'); ?> <br clear="all">

                  <div class="grid_item_price"> <?php echo money_format('%.2n', $collectible_for_sale->getPrice()); ?> </div>
                  <div class="grid_item_date"> <?php echo $collectible_for_sale->getCreatedAt('%Y-%m-%d'); ?> </div>
                  <br clear="all">
                  <?php $offers = $collectible_for_sale->getOffersCount(true);
                  if ($offers > 0): ?>
                    <div
                      style="width: 150px; text-align: center; background: #E9B26F; padding: 3px; margin-top: 2px; color: #fff;"> <?php echo link_to(sprintf('view offers (%d)', $offers), 'marketplace_item_offers', $collectible_for_sale, array('style' => 'color: #fff;')); ?> </div>
                    <?php else: ?>
                    <div
                      style="width: 150px; text-align: center; background: #81B5C8; padding: 3px; margin-top: 2px; color: #fff;">
                      no offers
                    </div>
                  <?php endif; ?>
                </div>
              <?php endforeach;
            else: ?>
              <div style="margin: 0 10px 10px 10px;"> You have no items for sale in the marketplace!</div>
            <?php endif; ?>
          </div>
          <div id="collectibles_sold" class="ui-tabs-hide" style="padding-left: 13px;">
            <?php
            foreach ($collectibles_sold as $collectible_for_sale):
              $collectible = $collectible_for_sale->getCollectible();
              ?>
                <div id="item_<?php echo $collectible->getId(); ?>" class="grid_item" style="margin-left: 10px;">
                   <?php echo link_to_collectible($collectible, 'image'); ?>
                <br clear="all">
                <?php $offers = $collectible_for_sale->getOffersCount();
                if ($offers > 0): ?>
                  <div
                    style="width: 150px; text-align: center; background: #E9B26F; padding: 3px; margin-top: 2px; color: #fff;"> <?php echo link_to(sprintf('view offers (%d)', $offers), 'marketplace_item_offers', $collectible_for_sale, array('style' => 'color: #fff;')); ?> </div>
                  <?php else: ?>
                  <div
                    style="width: 150px; text-align: center; background: #81B5C8; padding: 3px; margin-top: 2px; color: #fff;">
                    <b> no offers </b></div>
                <?php endif; ?>
              </div>
            <?php endforeach;
            if (empty($collectibles_sold)): ?>
              <div style="margin: 0 10px 10px 10px;"> You haven't sold any items yet! </div>
            <?php endif; ?>
          </div>
          <div id="collectibles_buying" class="ui-tabs-hide" style="margin-left: 13px;">
            <?php
            foreach ($collectibles_buying as $offer):
              /* @var $collectible Collectible */
              $collectible = $offer->getCollectible();
              $collectible_for_sale = $offer->getCollectibleForSale();
              if (!$collectible_for_sale)
                continue;
              ?>
              <div id="item_<?php echo $collectible->getId(); ?>" class="grid_item" style="margin-left: 10px;">
                <br clear="all">
                <?php echo link_to_collectible($collectible, 'image') . '<br clear="all" />'; ?>
                <?php $price = $offer->getStatus() == 'counter' ? $offer->getPrice() : $collectible_for_sale->getPrice()  ?>
                <div class="grid_item_price">
                  <?php echo money_format('%.2n', $price); ?>
                </div>
                <div class="grid_item_date">
                  <?php echo $collectible_for_sale->getCreatedAt('%Y-%m-%d'); ?></div>
                <br clear="all">

                <div
                  style="width: 150px; text-align: center; background: #81B5C8; padding: 3px; margin-top: 2px; color: #fff;">
                    <?php
                    if ($offer->getStatus() == 'counter'):
                      echo 'countered';
                    else:
                      echo $offer->getStatus();
                    endif;
                    ?>
                </div>
              </div>
            <?php endforeach; ?>
            <?php if (!$collectibles_buying): ?>
              <p>You haven't bought any items yet!</p>
            <?php endif; ?>
          </div>
          <div id="collectibles_bought" class="ui-tabs-hide">
            <?php
              /** @var $collectible_offer CollectibleOffer */
              foreach ($collectibles_bought as $collectible_offer):
                $collectible = $collectible_offer->getCollectible();
            ?>
              <div id="item_<?php echo $collectible->getId(); ?>" class="grid_item">
                <?= link_to_collectible($collectible, 'image'); ?>
              </div>
            <?php endforeach; ?>

            <?php if (empty($collectibles_bought)): ?>
              <div style="margin: 0 10px 10px 10px;">
                You haven't bought any items yet!
              </div>
            <?php endif; ?>
          </div>
        </div>
      </td>
    </tr>
  </table>
</div>
<script type="text/javascript">
  jQuery(document).ready(function() {
    jQuery('#marketplace').tabs();
  });
</script>
