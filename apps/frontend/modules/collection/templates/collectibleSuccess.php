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

<?php
  cq_page_title(
    $collectible->getName(),
    link_to('Back to Collection &raquo;', url_for_collection($collection))
  );
?>

<!--
  Test with alternate images: http://www.collectorsquest.next/collectible/3515/rkw-teacup
  Test without alternate images: http://collectorsquest.next/collectible/70081/space-set
//-->

<br/>
<div class="row-fluid" xmlns="http://www.w3.org/1999/html">
  <?php
    $span = 10;
    if (empty($additional_multimedia))
    {
      $span += 2;
    }
  ?>
  <div class="span<?= $span; ?>">
    <?php ice_image_tag_placeholder('504x398') ?>
    <?php ice_image_tag_flickholdr('620x490', array('tags' => array('Teacup', 'china', 'old'), 'i' => 1)) ?>
    <?php
      echo link_to(
        image_tag_collectible(
          $collectible, '620x0',
          array('width' => 620, 'height' => '', 'class' => 'magnify')
        ),
        src_tag_collectible($collectible, 'original'),
        array('id' => 'collectible_multimedia_primary', 'target' => '_blank')
      );
    ?>
  </div>

  <?php if (!empty($additional_multimedia)): ?>
  <div class="span2">
    <?php foreach ($additional_multimedia as $i => $m): ?>
    <a class="zoom" href="<?php echo src_tag_multimedia($m, '1024x768'); ?>" title="<?php echo $m->getName(); ?>" onClick="return false;">
      <?php ice_image_tag_flickholdr('100x100', array('tags' => array('Teacup', 'china', 'old'), 'i' => $i+2, 'style' => 'margin-bottom: 12px;')) ?>
      <?= image_tag_multimedia($m, '100x100', array('title' => $m->getName(), 'style' => 'margin-bottom: 12px;')); ?>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>

<br style="clear: both;">
<div class="statistics-share-panel bottom-margin-double">
  <div class="row-fluid">
    <div class="span4">
      <ul>
        <li>
          <span>XXXX Views</span>
        </li>
        <li>
          <span>In XXX wanted lists</span>
        </li>
      </ul>
    </div>
    <div class="span8 text-right">
      <a href="#" class="btn btn-mini-share2 btn-lightblue">
        <i class="add-icon-middle"></i> Add to your want list
      </a>
      <a href="#" class="btn btn-mini-share btn-lightblue">
        <i class="mail-icon-mini"></i> Mail
      </a>
      <a class="btn-mini-social" href="http://facebook.com/Collectors.Quest" target="_blank" >
        <i class="s-16-icon-facebook social-ico-padding"></i>
      </a>
      <a class="btn-mini-social" href="http://twitter.com/CollectorsQuest" target="_blank" >
        <i class="s-16-icon-twitter social-ico-padding"></i>
      </a>
      <a class="btn-mini-social" href="#" target="_blank" >
        <i class="s-16-icon-google social-ico-padding"></i>
      </a>
      <a class="btn-mini-social" href="http://pinterest.com/CollectorsQuest" target="_blank">
        <i class="s-16-icon-pinterest social-ico-padding"></i>
      </a>
    </div>
  </div>
</div>

<?php if ($collectible->getDescription('stripped')): ?>
<div class="item-description">
  <h3>
  <?php
    if ($sf_user->isOwnerOf($collectible))
    {
      echo __('This is what you said about this collectible:');
    }
    else
    {
      echo sprintf(__('What %s says about this collectible:'), link_to_collector($collectible, 'text'));
    }
  ?>
  </h3>
  <br style="clear:both;"/>
  <div>
    <dd id="collectible_<?= $collectible->getId(); ?>_description"
        style="border-left: 2px solid #eee; padding-left: 15px; font-size: 14px;"
      ><?= $collectible->getDescription('html'); ?></dd>
  </div>
</div>
<br style="clear:both;"/>
<?php endif; ?>

<?php include_partial('sandbox/comments'); ?>
Permalink: <span class="lightblue"><?= url_for_collectible($collectible, true) ?></span>

<!-- sale items -->
<div class="item-description">
  <h2 class="Chivo webfont">
    Akkiloki Peecol, 1st (Limited) edition Peecol by eboy for Kidrobot
  </h2>
  <p>This arctic princess loves listening to Bjork and hitting the slopes on her snowboard when she isn't too busy studying for school. For kicks, visit her <a href="#">MySpace page</a>! Akkiloki is opened, but comes with the original box.</p>
  <p>
  Size: 3.5 Inches/9 cm<br>
  Material: ABS plastic<br>
  Box Size: 4.5 x 2.5 x 1.6 inches<br>
  Box Weight: 1.6 oz<br>
  </p>
  <span class="item-condition">Condition:</span> Like new
</div>


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
  <p>Have a question about shippng? <a href="#">Send a message to Robotbacon »</a></p>
  <p>Return Policy: If you are unhappy with the item, I accept returns or exchanges for purchased items within 30 days of the shipping date. Please email me within 7 days of receiving your order to arrange for a refund or exchange. Returns or exchanges made without prior notification may not be processed. Product must be returned in the same condition as it was received. Shipping charges are non-refundable and are full responsiblity of customer. Your refund will be issued when return items are received. In case of receiving damaged item, please return the item (you will be compensated for shipping costs).</p>
  <p>Payment: I accept payment through PayPal, Moneybookers, money order and bank transfer. I greatly appreciate prompt payment and/or prompt communication regarding payment. I will not ship until payment has been received.</p>
</div>

<div id="price-container">
  <span class="price-large">
    $25.00
  </span>
  <button type="submit" class="btn btn-primary blue-button" value="Add Item to Cart">
    <i class="add-to-card-button"></i>
    <span>Add Item to Cart</span>
  </button>
</div>

<div class="t-b-margin">
  Permalink: <span class="lightblue"><?= url_for_collectible($collectible, true) ?></span>
</div>
