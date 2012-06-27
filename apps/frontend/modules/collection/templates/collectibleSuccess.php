<?php
/**
 * @var  $sf_user  cqFrontendUser
 *
 * @var  $collector    Collector
 * @var  $collection   Collection
 * @var  $collectible  Collectible
 * @var  $collectible_for_sale  CollectibleForSale
 *
 * @var  $additional_multimedia iceModelMultimedia[]
 */

  $buy_form = new CollectibleForSaleBuyForm($collectible_for_sale);

  $shipping_will_ship_text = '';
  $shipping_no_shipping_countries = array();
?>

<?php
  $options = array('id' => sprintf('collectible_%d_name', $collectible->getId()));
  if ($editable) {
    $options['class'] = 'row-fluid header-bar editable';
  }
  cq_page_title($collectible->getName(), null, $options);
?>

<?/**
<div class="row-fluid spacer-top-15">
  <div class="span10 text-center">
    <a href="#" target="_blank" id="collectible_multimedia_primary">
      <img title="iMac G4 circa 2002" src="http://placehold.it/620x480" class="magnify">
    </a>
  </div>
  <div class="span2">
    <div class="carousel-v-main">
      <a id="ui-carousel-v-next" class="step-up" title="next" href="#">
        <i class="icon-chevron-up white"></i>
      </a>
      <a id="ui-carousel-v-prev" class="step-down" title="previous" href="#">
        <i class="icon-chevron-down white"></i>
      </a>

      <a title="" href="#" class="zoom collectible">
        <img src="http://placehold.it/92x92" style="" alt="" title="">
      </a>
      <a title="" href="#" class="zoom collectible">
        <img src="http://placehold.it/92x92" alt="" title="">
      </a>
      <a title="" href="#" class="zoom collectible">
        <img src="http://placehold.it/92x92" alt="" title="">
      </a>
    </div>
  </div>
</div>
*/?>

<div class="row-fluid spacer-top-15">
  <?php
    $span = 10;
    if (count($additional_multimedia) == 0)
    {
      $span += 2;
    }
  ?>
  <div class="span<?= $span; ?> text-center">
    <?php
      echo link_to(
        image_tag_collectible(
          $collectible, '620x0',
          array('height' => null, 'class' => 'magnify')
        ),
        src_tag_collectible($collectible, 'original'),
        array('id' => 'collectible_multimedia_primary', 'target' => '_blank')
      );
    ?>
  </div>

  <?php if (count($additional_multimedia) > 0): ?>
  <div class="span2">
    <a href="#" id="ui-carousel-prev" title="previous collectible" class="ui-carousel-navigation hidden left-arrow">
      <i class="icon-chevron-left white"></i>
    </a>
    <div id="vertical-carousel">
      <a class="zoom" href="<?php echo src_tag_collectible($collectible, '150x150'); ?>" title="<?php echo $collectible->getName(); ?>">
        <?= image_tag_collectible($collectible, '150x150', array(
          'height' => null, 'title' => $collectible->getName(), 'style' => 'margin-bottom: 12px;')); ?>
      </a>
      <?php foreach ($additional_multimedia as $i => $m): ?>
      <a class="zoom" href="<?php echo src_tag_multimedia($m, 'original'); ?>" title="<?php echo $m->getName(); ?>">
        <?= image_tag_multimedia($m, '150x150', array('height' => null, 'title' => $m->getName(), 'style' => 'margin-bottom: 12px;')); ?>
      </a>
      <?php endforeach; ?>
    </div>
    <a href="#" id="ui-carousel-next" title="next collectible" class="ui-carousel-navigation hidden right-arrow">
      <i class="icon-chevron-right white"></i>
    </a>
  </div>
  <?php endif; ?>
</div>

<div class="blue-actions-panel spacer-20">
  <div class="row-fluid">
    <div class="pull-left">
      <ul>
        <li>
          <?php
            echo format_number_choice(
              '[0] no views yet|[1] 1 View|(1,+Inf] %1% Views',
              array('%1%' => number_format($collectible->getNumViews())), $collectible->getNumViews()
            );
          ?>
        </li>
        <!--
          <li>In XXX wanted lists</li>
        //-->
      </ul>
    </div>
    <div class="pull-right share">
      <!-- AddThis Button BEGIN -->
      <a class="btn-lightblue btn-mini-social addthis_button_email">
        <i class="mail-icon-mini"></i> Email
      </a>
      <a class="addthis_button_facebook_like" fb:like:layout="button_count" fb:like:width="40"></a>
      <a class="addthis_button_tweet" tw:twitter:data-count="none"></a>
      <a class="addthis_button_google_plusone" g:plusone:size="medium" g:plusone:annotation="none"></a>
      <a class="addthis_button_pinterest_pinit" pi:pinit:media="<?= src_tag_collectible($collectible, 'original'); ?>" pi:pinit:layout="horizontal"></a>
      <!-- AddThis Button END -->
    </div>
  </div>
</div>

<?php if ($collectible->getDescription('stripped')): ?>
<div class="item-description <?= $editable ? 'editable_html' : '' ?>"
     id="collectible_<?= $collectible->getId(); ?>_description">
  <?= $collectible->getDescription('html'); ?>
</div>
<?php endif; ?>

<?php if (isset($collectible_for_sale) && $collectible_for_sale->isForSale() && $collectible_for_sale->hasActiveCredit()): ?>
  <!-- sale items -->
  <span class="item-condition"><strong>Condition:</strong> <?= $collectible_for_sale->getCondition(); ?></span>

  <table class="shipping-rates">
    <thead>
    <tr class="shipping-dest">
      <th colspan="2">
        <strong>Shipping from:</strong> <span class="darkblue">
          <?= $collector->getProfile()->getCountry() ?: '-'; ?>
        </span>
      </th>
    </tr>
    <tr class="dotted-line-brown">
      <th>SHIP TO</th>
      <th>COST</th>
    </tr>
    </thead>
    <tbody>
    <?php if (count($collectible->getShippingReferencesByCountryCode())):
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

      <?= $shipping_will_ship_text; // first output which countries we ship to ?>

      <?php $international_shipping = $collectible->getShippingReferenceForCountryCode('ZZ'); ?>
      <?php if ($international_shipping && ShippingReferencePeer::SHIPPING_TYPE_NO_SHIPPING == $international_shipping->getShippingType()): ?>
        <tr>
          <td>International Shipping</td>
          <td>This item cannot be shipped internationally</td>
        </tr>
      <?php elseif ($shipping_no_shipping_countries): ?>
        <tr>
          <td>This item cannot be shipped to the following countries</td>
          <td><?= implode($shipping_no_shipping_countries, ', '); ?></td>
        </tr>
      <?endif; ?>
    <?php else: // if has shipping references ?>
      <tr>
        <td>United States</td>
        <td>Free shipping</td>
      </tr>
      <tr>
        <td>Everywhere Else</td>
        <td>Free shipping</td>
      </tr>
    <?php endif; // if has shipping references ?>
    </tbody>
  </table>


  <div id="information-box">
    <p>Have a question about shipping? <?= cq_link_to(
      sprintf('Send a message to %s »', $collector->getDisplayName()),
      'messages_compose',
      array('to' => $collector->getUsername(), 'subject' => 'Regarding your item: '. $collectible->getName(), 'goto' => $sf_request->getUri())
    ); ?></p>

    <?php if ($collector->getSellerSettingsReturnPolicy()): ?>
      <p>Return Policy: <?= $collector->getSellerSettingsReturnPolicy(); ?></p>
    <?endif; ?>

    <?php if ($collector->getSellerSettingsPaymentAccepted()): ?>
      <p>Payment: <?= $collector->getSellerSettingsPaymentAccepted(); ?></p>
    <?php endif; ?>
  </div>


  <?php if ($collectible_for_sale->getIsSold()): ?>
  <div id="price-container">
    <p class="price">
      Sold
      <small>
        for <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>
      </small>
    </p>
    Quantity sold: 1
  </div>

  <?php elseif ($collectible_for_sale->isForSale() && IceGateKeeper::open('shopping_cart')): ?>
  <form action="<?= url_for('@shopping_cart', true); ?>" method="post">
    <div id="price-container">
      <p class="price">
        <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>

        <?php if ($collectible_for_sale->isShippingFree()): ?>
          <small style="white-space: nowrap;">with FREE shipping & handling</small>
        <?php endif; ?>
      </p>
      <button type="submit" class="btn btn-primary pull-left" value="Add Item to Cart">
        <i class="add-to-card-button"></i>
        <span>Add Item to Cart</span>
      </button>
    </div>

    <?= $buy_form->renderHiddenFields(); ?>
  </form>
  <?php endif; // if for sale ?>


<?php else: // if not (for sale && has active credit) ?>

  <?php
    include_partial(
      'comments/comments',
      array('for_object' => $collectible->getCollectible())
    );
  ?>

<?php endif; ?>

<script>
$(document).ready(function()
{
  'use strict';

  var $vertical_carousel = $('#vertical-carousel');

  // enable vertical carousel only if we have more than 3 alternative views
  if ($vertical_carousel.children().length > 3) {
    // show navigation arrows
    $vertical_carousel.siblings('.ui-carousel-navigation').removeClass('hidden');

    // enable carousel
    $vertical_carousel.rcarousel({
      orientation: 'vertical',
      visible: 3, step: 3,
      auto: { enabled: true, interval: 15000 }
    });
  }


  $(".zoom").click(function(e)
  {
    e.stopPropagation();

    var source = $(this).find('img');
    var target = $('#collectible_multimedia_primary');
    var path = $(source).attr('src').split(/\/150x150\//);

    $(target)
      .attr('href', path[0] + '/original/' + path[1])
      .find('img')
      .attr({
        src: path[0] + '/620x0/' + path[1],
        alt: $(source).attr('alt')
      });

    return false;
  });
});
</script>
