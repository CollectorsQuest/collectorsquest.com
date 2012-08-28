<?php

  use_javascript('jquery/rcarousel.js');

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

<?php slot('prev_next'); ?>
  <link rel="prev" href="<?= url_for_collectible($previous) ?>">
  <link rel="next" href="<?= url_for_collectible($next) ?>">
  <link rel="start" href="<?= url_for_collectible($first) ?>">
<?php end_slot(); ?>

<?php
  $options = array(
    'id' => sprintf('collectible_%d_name', $collectible->getId()),
    'class' => isset($editable) && true === $editable ? 'row-fluid header-bar editable' : 'row-fluid header-bar'
  );

  cq_page_title($collectible->getName(), null, $options);
?>

<div class="row-fluid main-collectible-container">
  <?php
    $span = 10;
    if (count($additional_multimedia) == 0)
    {
      $span += 2;
    }
  ?>
  <div class="span<?= $span; ?> text-center relative">

    <?php if ($previous): ?>
    <a href="<?= url_for_collectible($previous) ?>"
       class="prev-collectible" title="Previous: <?= $previous->getName(); ?>">
      <span>prev</span>
      <i class="icon-caret-left white"></i>
    </a>
    <?php endif; ?>

    <?php if ($next): ?>
    <a href="<?= url_for_collectible($next) ?>"
       class="next-collectible" title="Next: <?= $next->getName(); ?>">
      <span>next</span>
      <i class="icon-caret-right white"></i>
    </a>
    <?php endif; ?>

    <a class="zoom-zone" target="_blank" title="Click to zoom"
       href="<?= src_tag_collectible($collectible, 'original') ?>">
      <span class="picture-zoom holder-icon-edit">
        <i class="icon icon-zoom-in"></i>
      </span>
    </a>
    <?php
      echo link_to(
        image_tag_collectible(
          $collectible, '620x0',
          array('width' => null, 'height' => null)
        ),
        src_tag_collectible($collectible, 'original'),
        array('id' => 'collectible_multimedia_primary', 'target' => '_blank')
      );
    ?>
  </div>

  <?php if (count($additional_multimedia) > 0): ?>
  <div class="span2">
    <div class="vertical-carousel-wrapper">
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
      <a href="javascript:void(0)" id="ui-carousel-prev" title="previous collectible" class="ui-carousel-navigation hidden left-arrow">
        <i class="icon-chevron-up white"></i>
      </a>
      <a href="javascript:void(0)" id="ui-carousel-next" title="next collectible" class="ui-carousel-navigation hidden right-arrow">
        <i class="icon-chevron-down white"></i>
      </a>
    </div>
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
    <div id="social-sharing" class="pull-right share">
      <!-- AddThis Button BEGIN -->
      <a class="btn-lightblue btn-mini-social addthis_button_email">
        <i class="mail-icon-mini"></i> Email
      </a>
      <a class="addthis_button_pinterest_pinit" pi:pinit:media="<?= src_tag_collectible($collectible, 'original'); ?>" pi:pinit:layout="horizontal"></a>
      <a class="addthis_button_tweet" tw:twitter:data-count="none"></a>
      <a class="addthis_button_google_plusone" g:plusone:size="medium" g:plusone:annotation="none"></a>
      <a class="addthis_button_facebook_like" fb:like:layout="button_count" fb:like:width="40"></a>
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
          <small class="text-nowrap">with FREE shipping & handling</small>
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

  // enable vertical carousel only if we have more than 3 alternate views
  if ($vertical_carousel.children().length > 3)
  {
    // show navigation arrows
    $vertical_carousel.siblings('.ui-carousel-navigation').removeClass('hidden');

    // enable carousel
    $vertical_carousel.rcarousel({
      orientation: 'vertical',
      visible: 3, step: 3,
      margin: 14,
      height: 92, width: 92,
      auto: { enabled: true, interval: 15000 }
    });
  }

  $vertical_carousel.on('click', '.zoom', function(e)
  {
    var $source = $(this).find('img');
    var $target = $('#collectible_multimedia_primary');
    var path = $source.attr('src').split(/\/150x150\//);

    $target
      .attr('href', path[0] + '/original/' + path[1])
      .find('img')
      .attr({
        src: path[0] + '/620x0/' + path[1],
        alt: $source.attr('alt')
      })
      .data('id', $source.data('id'));

    $target
      .siblings('a.zoom-zone')
      .attr('href', path[0] + '/original/' + path[1]);

    return false;
  });

  $('a.zoom-zone').click(function(e)
  {
    e.preventDefault();

    var url = '<?= url_for('@ajax_multimedia?which=940x0'); ?>';
    var $a = $(this);
    var $img = $('img.multimedia', $a.parent());
    var $div = $('<div></div>');

    $img.showLoading();
    $div.appendTo('body').load(url + '&id=' + $img.data('id'), function()
    {
      $('img.multimedia', this).load(function()
      {
        var width = $(this).attr('width');
        var height = $(this).attr('height');

        var margin = -1 * (width / 2 - 280);

        $('.modal', $div).addClass('rounded-bottom');
        $('.modal', $div).css('width', width);
        $('.modal', $div).css('margin-left', margin + 'px');
        $('.modal', $div).modal('show');

        $img.hideLoading();
      });
    });

    return false;
  });

});
</script>
