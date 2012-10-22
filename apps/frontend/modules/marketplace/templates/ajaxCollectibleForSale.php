<?php
/**
 * @var $collectible Collectible
 * @var $collection  Collection
 */
?>

<div class="modal modal-mwba not-rounded" data-dynamic="true" tabindex="-1">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 style="width: 500px;"><?= $collectible->getName() ?></h3>
  </div>
  <div  id="modal-collectible-for-sale" class="modal-body opened" style="max-height: none;">
    <div class="row-fluid">
      <div class="span5">
        <?php
          echo link_to(
            image_tag_collectible(
              $collectible, '620x0',
              array('width' => null, 'height' => null)
            ),
            url_for_collectible($collectible), array('target' => '_blank')
          );
        ?>
      </div>
      <div class="span7">
        <form action="<?= url_for('@shopping_cart', true); ?>" method="post">
          <div id="price-container" class="price">
            <p class="price">
              <span>
                <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>
              </span>

              <?php if ($collectible_for_sale->isShippingFree()): ?>
              <small class="text-nowrap">with FREE shipping & handling</small>
              <?php endif; ?>
            </p>
            <button type="submit" class="btn btn-primary pull-left" value="Add Item to Cart">
              <i class="add-to-card-button"></i>
              Add Item to Cart
            </button>

            <?php
              echo link_to('View Item',
                url_for_collectible($collectible_for_sale->getCollectible()),
                array('class' => 'btn btn-primary pull-left', 'style' => 'margin-left: 10px;')
              );
            ?>
          </div>

          <?= $form->renderHiddenFields(); ?>
        </form>

        <?php if ($collectible->getDescription('stripped')): ?>
          <div class="item-description" id="collectible_<?= $collectible->getId(); ?>_description">
            <?= $description = $collectible->getDescription('stripped', 200); ?>
          </div>
        <?php endif; ?>

      </div>
      <div class="span12" style="margin-left: 0;">
        <table class="shipping-rates">
          <thead>
          <tr class="shipping-dest">
            <th colspan="2">
              <strong>Shipping from:</strong> <span class="darkblue">
          <?= $collector->getProfile()->getCountryName() ?: '-'; ?>
        </span>
            </th>
          </tr>
          <tr class="dotted-line-brown">
            <th>SHIP TO</th>
            <th>COST</th>
          </tr>
          </thead>
          <tbody>

          <?php if (count($collectible->getShippingReferencesByCountryCode())): $shipping_will_ship_text = null;
            foreach ($collectible->getShippingReferencesByCountryCode() as $country_code => $shipping_reference):
              if (ShippingReferencePeer::SHIPPING_TYPE_NO_SHIPPING != $shipping_reference->getShippingType()): ?>

                <?php ob_start(); ?>
              <tr>
                <td><?= $shipping_reference->getCountryName(); ?></td>
                <td>
                  <?php if ($shipping_reference->isSimpleFreeShipping()): ?>
                  Free shipping
                  <?php else: ?>
                  $<?= $shipping_reference->getSimpleShippingAmount(); ?>
                  <?php endif; ?>
                </td>
              </tr>
                <?php $shipping_will_ship_text .= ob_get_clean(); ?>

                <?php else: // shipping_type = no shipping
                $shipping_no_shipping_countries[] = $shipping_reference->getCountryName();

              endif;
            endforeach; // foreach shipping reference ?>

            <?= (isset($shipping_will_ship_text)) ? $shipping_will_ship_text : null; ?>

            <?php $international_shipping = $collectible->getShippingReferenceForCountryCode('ZZ'); ?>
            <?php if ($international_shipping && ShippingReferencePeer::SHIPPING_TYPE_NO_SHIPPING == $international_shipping->getShippingType()): ?>
            <tr>
              <td>International Shipping</td>
              <td>This item cannot be shipped internationally</td>
            </tr>
              <?php elseif (isset($shipping_no_shipping_countries)): ?>
            <tr>
              <td>This item cannot be shipped to the following countries</td>
              <td><?= implode($shipping_no_shipping_countries, ', '); ?></td>
            </tr>
              <?php endif; ?>
            <?php else: ?>
          <tr>
            <td>United States</td>
            <td>Free shipping</td>
          </tr>
          <tr>
            <td>Everywhere Else</td>
            <td>Free shipping</td>
          </tr>
            <?php endif; ?>
          </tbody>
        </table>

        <div id="information-box">
          <p>Have a question about shipping? <?= cq_link_to(
            sprintf('Send a message to %s »', $collector->getDisplayName()),
            'messages_compose',
            array(
              'to' => $collector->getUsername(),
              'subject' => 'Regarding your item: '. $collectible->getName(),
              'goto' => url_for_collectible($collectible)
            )
          ); ?></p>

          <?php if ($refunds_policy = $collector->getSellerSettingsRefunds()): ?>
          <p><strong>Refunds Policy:</strong> <?= $refunds_policy ?></p>
          <?php endif; ?>

          <?php if ($shipping_policy = $collector->getSellerSettingsShipping()): ?>
          <p><strong>Shipping Policy:</strong> <?= $shipping_policy; ?></p>
          <?php endif; ?>
        </div>
      </div>
  </div>
</div>
