<?php use_helper('Form'); ?>
<?php

/** @var $collectible Collectible */
/** @var $sf_context sfContext */
/** @var $sf_user cqUser */

/** @var $collectible_for_sale CollectibleForSale */
$collectible_for_sale = $collectible->getForSaleInformation();

$offerPrice = 0;
$isSold = false;
if ($collectible_for_sale)
{
  $offer = $collectible_for_sale->getCollectibleOfferByBuyer($sf_user->getId(), 'counter');
  $offerPrice = $offer ? $offer->getPrice() : $collectible_for_sale->getPrice();
  $isSold = $collectible_for_sale->getIsSold() || $collectible_for_sale->getActiveCollectibleOffersCount();
}
?>
<div class="span-11" style="margin: 10px 10px 10px 15px; padding: 5px; background: #F5F8DD; text-align: center;">
  <div id="fancybox-outer">
    <?php
    echo link_to(
      image_tag_collectible($collectible, '420x1000', array('max_width' => 420, 'class' => 'magnify', 'style' => 'margin-top: 5px;')), src_tag_collectible($collectible, '1024x768'), array('id' => 'collectible_multimedia_primary')
    );
    ?>
    <?php if ($previous): ?>
      <a id="fancybox-left"
         href="<?php echo url_for(sprintf('@collectible_by_slug?id=%d&slug=%s', $previous->getId(), $previous->getSlug())); ?>"
         style="display: inline;"><span id="fancybox-left-ico" class="fancy-ico"></span></a>
       <?php endif; ?>
       <?php if ($next): ?>
      <a id="fancybox-right"
         href="<?php echo url_for(sprintf('@collectible_by_slug?id=%d&slug=%s', $next->getId(), $next->getSlug())); ?>"
         style="display: inline;"><span id="fancybox-right-ico" class="fancy-ico"></span></a>
       <?php endif; ?>
  </div>

  <?php if (!empty($additional_multimedia)): ?>
    <div id="alternate_views">
      <?php foreach ($additional_multimedia as $m): ?>
        <div class="span-3 alternative" style="margin-left: 25px; margin-top: 10px; margin-bottom: 25px; width: 100px; height: 100px;">
          <a class="zoom" href="<?php echo src_tag_multimedia($m, '1024x768'); ?>" title="<?php echo $m->getName(); ?>" onClick="return false;">
            <?php echo image_tag_multimedia($m, '150x150', array('width' => 100, 'title' => $m->getName())); ?>
          </a>
          <?php echo image_tag('legacy/zoom.png', array('class' => 'zoom-overlay')); ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
<div class="span-8 last" style="padding-top: 10px; margin-right: -25px; margin-bottom: 15px;">
  <a href="<?php echo url_for(sprintf('@collectible_by_slug?id=%d&slug=%s', $previous->getId(), $previous->getSlug())); ?>" class="prevPage browse left"></a>
  <div class="scrollable">
    <img src="/images/loading.gif" alt="loading..." class="loading" style="margin: 45px 0 0 90px;"/>
    <ul style="display: none;">
      <?php
      foreach (array($previous, $next) as $c)
      {
        if (!$c instanceof Collectible)
          continue;

        echo '<li style="margin: 0 0px;">';
        echo link_to_collectible(
          $c, 'image', array(
          'width' => 75, 'height' => 75,
          'rel' => url_for('@collectible_by_slug?id=' . $c->getId() . '&slug=' . $c->getSlug())
          )
        );
        echo '</li>';
      }
      ?>
    </ul>
  </div>
  <a href="<?php echo url_for(sprintf('@collectible_by_slug?id=%d&slug=%s', $next->getId(), $next->getSlug())); ?>" class="nextPage browse right"></a>
</div>

<?php if ($collectible_for_sale and $collectible_for_sale->getIsReady() and (!$sf_user->isAuthenticated() or $sf_user->getCollector()->getId() !== $collectible_for_sale->getCollector()->getId())): ?>
  <div class="rounded buynow" style="float:right; margin-right: 90px;">
    <?php if (!$isSold): ?>
      <a href="<?php echo url_for('marketplace_buy_now', $collectible_for_sale) ?>">
        <?php echo ($offerPrice > 0) ? money_format('Buy for <br /> %.2n', (float) $offerPrice) : __('Buy Item'); ?>
      </a>
    <?php else: ?>
      <?php echo __('SOLD FOR <br />%price%', array('%price%'=>money_format('%.2n', $collectible_for_sale->getPrice()))); ?>
    <?php endif; ?>
  </div>
<?php endif; ?>

<?php if ($collectible->getDescription('stripped')): ?>
  <br style="clear:both;"/><br/>
  <div class="span-18 last" style="padding: 10px; margin-left: 8px;">
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

    <div class="append-1">
      <?php if ($sf_user->isOwnerOf($collectible)): ?>
        <span class="ui-icon ui-icon-pencil ui-icon-editable"></span>
      <?php endif; ?>
      <dd id="collectible_<?= $collectible->getId(); ?>_description"
          class="<?= ($sf_user->isOwnerOf($collectible)) ? 'editable_html' : '' ?>"
          style="border-left: 2px solid #eee; padding-left: 15px; font-size: 14px;"
          ><?= $collectible->getDescription('html'); ?></dd>
    </div>
  </div>
  <br clear="all"/>
  <?php elseif ($sf_user->isOwnerOf($collectible)): ?>
    <br style="clear:both;"><br/>
    <div class="span-18 last" style="padding: 10px; margin-left: 8px;">
    <h3><?= sprintf(__('%s, please share your thoughts about this collectible:'), $sf_user->getDisplayName()); ?></h3>
    <div class="append-1"><span class="ui-icon ui-icon-pencil ui-icon-editable"></span>
    <dd id="collectible_<?= $collectible->getId(); ?>_description" class="editable_html"
    style="border-left: 2px solid #eee; padding-left: 15px; font-size: 14px;"
    ><?php echo __('(click here to edit)'); ?></dd>
    </div>
    </div>
    <br clear="all"/>
<?php endif; ?>

<?php if (!$sf_user->isOwnerOf($collectible)): ?>
  <div class="span-18 last" style="padding: 10px; margin-left: 8px;">
    <fb:like href="<?= $sf_request->getUri(); ?>" send="true" width="728" show_faces="true"></fb:like>

    <?php slot('facebook_metas'); ?>
      <meta property="og:title" content="<?php echo htmlspecialchars($collectible->getName(), ENT_QUOTES) ?>" />
      <meta property="og:type" content="product" />
      <?php if ($multimedia = $collectible->getMultimedia(true)): ?>
        <meta property="og:image" content="<?= src_tag_multimedia($multimedia, '150x150'); ?>" />
      <?php endif; ?>
    <?php end_slot(); ?>
  </div>
<?php endif; ?>

<?php
if ($collectible->isForSale() and $collectible_for_sale and $collectible_for_sale->getIsReady() and !$isSold)
{
  include_partial('collection/buy_collectible', array('collectible' => $collectible, 'collector' => $collector));
}
?>

<?php if ($collectible->hasTags()): ?>
  <div class="span-1 prepend-1" style="padding-top: 5px; margin-right: 0;"><?php echo image_tag('icons/fatcow/16x16/tag_orange.png'); ?></div>
  <div class="span-16" style="font-size: 120%;"><?php echo cq_tags_for_collectible($collectible); ?></div>
<?php endif; ?>

<br class="clear"/><br/>
<?php if (false && !$sf_user->isAuthenticated()): ?>
  <div class="span-19 append-bottom last">
    <?php cq_ad_slot('collectorsquest_com_-_After_Listing_728x90', '728', '90'); ?>
  </div>
<?php endif; ?>

<?php
include_component('comments', 'commentList', array('object' => $collectible));
include_component('comments', 'commentForm', array('object' => $collectible));
?>

<?php cq_javascript_tag(); ?>
<script type="text/javascript">
  $('div.scrollable').ready(function()
  {
    $('div.scrollable .loading').hide();
    $('div.scrollable ul').show();
    $("div.scrollable").jCarouselLite(
    {
      btnNext: "a.nextPage", btnPrev: "a.prevPage",
      mouseWheel: false, visible: 2, scroll: 2, circular: false, start: 0
    });
  });

  $(function()
  {
    $('div.alternative').mouseover(function()
    {
      $(this).children('img.zoom-overlay').show();
    })
    $('div.alternative').mouseout(function()
    {
      $(this).children('img.zoom-overlay').hide();
    })
    $('img.zoom-overlay').click(function()
    {
      $(this).siblings('a').click();
    });

    $("a.zoom, #collectible_multimedia_primary").imgbox(
    {
      autoScale: true,
      hideOnOverlayClick: true,
      hideOnContentClick: true,
      allowMultiple: false
    });
  });
</script>
<?php cq_end_javascript_tag(); ?>
