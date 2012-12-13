<?php /** @var $shopping_order ShoppingOrder */ ?>
<?php if ($shopping_order): ?>
<div class="well">
  <?= image_tag_collectible($shopping_order->getCollectible(), '260x205'); ?>
  <br/><br/>
  <table style="width: 100%;">
    <tr>
      <td colspan="2"><h3>
        <?= link_to_collectible($shopping_order->getCollectible(), 'text', array('target' => '_blank')); ?>
      </h3></td>
    </tr>
    <tr>
      <td colspan="2"><hr/></td>
    </tr>
    <tr>
      <td>Quantity:</td>
      <td class="text-right">
        1 <strong>x</strong> <?= money_format('%.2n', (float) $shopping_order->getCollectiblesAmount()); ?>
      </td>
    </tr>
    <?php if ($promotion = $shopping_order->getSellerPromotion()): ?>
      <tr>
        <td><?= $promotion->getPromotionName() ?></td>
        <td class="text-right">
          - <?= money_format('%.2n', (float) $shopping_order->getPromotionAmount()); ?>
        </td>
      </tr>
    <?php endif; ?>
    <?php if (0 != (int) $shopping_order->getCollectibleForSale()->getTaxPercentage()): ?>
      <tr class="with_tax<?= 0 == (int) $shopping_order->getTaxAmount() ? ' hide' : ''?>">
          <td>Tax (<?= $shopping_order->getCollectibleForSale()->getTaxPercentage() ?>%):</td>
          <td class="text-right">
              <?= money_format('%.2n', (float) $shopping_order->getTaxAmount('float',
            $shopping_order->getCollectibleForSale()->getTaxPercentage())); ?>
          </td>
      </tr>
    <?php endif; ?>
    <tr>
      <td style="vertical-align: top;">Shipping:</td>
      <td class="text-right">

      <?php if (null === $shopping_order->getShippingFeeAmount()): ?>
        <span class="red">Cannot be shipped to <br/>the chosen country!</span>
      <?php elseif (0 === $shopping_order->getShippingFeeAmount('integer')): ?>
        Free
      <?php else: ?>
        <?= money_format('%.2n', (float) $shopping_order->getShippingFeeAmount()); ?>
      <?endif; ?>
      </td>
    </tr>
    <tr>
      <td colspan="2"><hr/></td>
    </tr>
    <tfoot style="font-weight: bold; font-size: 130%;">
      <tr>
        <td style="font-variant: small-caps;">Total:</td>
        <?php if (0 != (int) $shopping_order->getCollectibleForSale()->getTaxPercentage()): ?>
          <td class="with_tax<?= 0 == (int) $shopping_order->getTaxAmount() ? ' hide' : ''?>"
              style="text-align: right;">
            <?= money_format('%.2n', (float) $shopping_order->getTotalAmount('float',
            $shopping_order->getCollectibleForSale()->getTaxPercentage())); ?>
          </td>
          <td class="no_tax<?= 0 != (int) $shopping_order->getTaxAmount() ? ' hide' : ''?>"
              style="text-align: right;">
            <?= money_format('%.2n', (float) $shopping_order->getTotalAmount('float', 0)); ?>
          </td>
        <?php else: ?>
          <td style="text-align: right;">
            <?= money_format('%.2n', (float) $shopping_order->getTotalAmount()); ?>
          </td>
        <?php endif ?>
      </tr>
    </tfoot>
  </table>
</div>
<?php endif; ?>

<?php if (cqGateKeeper::open('shopping_checkout_help')): ?>

  <?php cq_sidebar_title('Checkout Help', null, array('style' => 'margin-top: 0;')); ?>
  <div style="padding: 10px;">
    <ul>
      <li><a href="#registerHelp">Do I have to log in?</a></li>
      <li><a href="#deliveryCostHelp">How much is delivery?</a></li>
      <li><a href="#secureShoppingHelp">Secure shopping</a></li>
      <li><a href="#privacyPolicyHelp">Privacy Policy</a></li>
      <li><a href="#currenciesHelp">Currencies</a></li>
      <li><a href="#deliveryHelp">Countries we deliver to</a></li>
      <li><a href="#phoneHelp">Why do you want my phone number?</a></li>
      <li><a href="#taxHelp">Tax</a></li>
      <li><a href="#howPayPal">How do I pay with PayPal?</a></li>
      <li><a href="#passwordHelp">I've forgotten my password</a></li>
      <li><a href="#contactHelp">How can I contact you?</a></li>
    </ul>
  </div>

<?php endif; ?>
