<?php
/**
 * @var  $sf_user  cqFrontendUser
 *
 * @var  $collector    Collector
 * @var  $collection   Collection
 * @var  $collectible  Collectible
 *
 * @var  $additional_multimedia iceModelMultimedia[]
 */
?>

<?php cq_page_title($collectible->getName(), null); ?>

<!--
  Test with alternate images: http://www.collectorsquest.next/collectible/3515/rkw-teacup
  Test without alternate images: http://collectorsquest.next/collectible/70081/space-set
//-->

<br/>
<div class="row-fluid" xmlns="http://www.w3.org/1999/html">
  <?php
    $span = 10;
    if (count($additional_multimedia) == 0)
    {
      $span += 2;
    }
  ?>
  <div class="span<?= $span; ?>" style="text-align: center;">
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
    <?php foreach ($additional_multimedia as $i => $m): ?>
    <a class="zoom" href="<?php echo src_tag_multimedia($m, 'original'); ?>" title="<?php echo $m->getName(); ?>" onClick="return false;">
      <?= image_tag_multimedia($m, '150x150', array('height' => null, 'title' => $m->getName(), 'style' => 'margin-bottom: 12px;')); ?>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>

<br style="clear: both;">
<div class="statistics-share-panel spacer-bottom-20">
  <div class="row-fluid">
    <div class="span4">
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
    <div class="span8 text-right">
      <!-- AddThis Button BEGIN -->
      <a class="addthis_button_email"></a>
      <a class="addthis_button_facebook_like" fb:like:layout="button_count" fb:like:width="40"></a>
      <a class="addthis_button_tweet" tw:twitter:data-count="none"></a>
      <a class="addthis_button_google_plusone" g:plusone:size="medium" g:plusone:annotation="none"></a>
      <a class="addthis_button_pinterest_pinit" pi:pinit:media="<?= image_tag_collectible($collectible, 'original'); ?>" pi:pinit:layout="horizontal"></a>
      <!-- AddThis Button END -->
    </div>
  </div>
</div>

<?php if ($collectible->getDescription('stripped')): ?>
<div class="item-description">
  <?= $collectible->getDescription('html'); ?>
</div>
<?php endif; ?>

<?php if (isset($collectible_for_sale) && $collectible_for_sale instanceof CollectibleForSale): ?>
  <!-- sale items -->
  <span class="item-condition"><strong>Condition:</strong> Like new</span>

  <table class="shipping-rates">
    <thead>
    <tr class="shipping-dest">
      <th colspan="5">
        <strong>Shipping from:</strong> <span class="darkblue">Portland, OR, USA</span>
      </th>
    </tr>
    <tr class="dotted-line-brown">
      <th>METHOD</th>
      <th>SHIP TO</th>
      <th>COST</th>
      <th>WITH ANOTHER ITEM</th>
      <th>ESTIMATED DELIVERY</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>USPS</td>
      <td>United States</td>
      <td>$3.00</td>
      <td>$2.00</td>
      <td>5-7 days</td>
    </tr>
    <tr>
      <td>USPS</td>
      <td>Everywhere Else</td>
      <td>$15.00</td>
      <td>$10.00</td>
      <td>2-3 weeks</td>
    </tr>
    </tbody>
  </table>


  <div id="information-box">
    <p>Have a question about shipping? <?= cq_link_to(sprintf('Send a message to %s »', $collector->getDisplayName()), '@messages_compose?to='. $collector->getUsername()); ?></p>
    <p>Return Policy: If you are unhappy with the item, I accept returns or exchanges for purchased items within 30 days of the shipping date. Please email me within 7 days of receiving your order to arrange for a refund or exchange. Returns or exchanges made without prior notification may not be processed. Product must be returned in the same condition as it was received. Shipping charges are non-refundable and are full responsiblity of customer. Your refund will be issued when return items are received. In case of receiving damaged item, please return the item (you will be compensated for shipping costs).</p>
    <p>Payment: I accept payment through PayPal, Moneybookers, money order and bank transfer. I greatly appreciate prompt payment and/or prompt communication regarding payment. I will not ship until payment has been received.</p>
  </div>

  <form action="<?= url_for('@shopping_cart', true); ?>" method="post">

    <div id="price-container">
      <span class="price-large">
        <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>
      </span>
      <?php if (!$sf_user->getCollector()->isOwnerOf($collectible_for_sale)): ?>
      <button type="submit" class="btn btn-primary blue-button" value="Add Item to Cart">
        <i class="add-to-card-button"></i>
        <span>Add Item to Cart</span>
      </button>
      <?php endif; ?>
    </div>

    <?= $form->renderHiddenFields(); ?>
  </form>

<?php else: ?>

  <?php include_partial('sandbox/comments'); ?>

<?php endif; ?>

<div class="spacer">
  Permalink: <span class="darkblue"><?= url_for_collectible($collectible, true) ?></span>
</div>
