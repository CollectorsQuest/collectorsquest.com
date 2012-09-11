<?php

/**
 * @var  $sf_user  cqFrontendUser
 *
 * @var  $collector    Collector
 * @var  $collection   Collection
 * @var  $collectible  Collectible
 * @var  $previous     Collectible
 * @var  $next         Collectible
 * @var  $first        Collectible
 * @var  $collectible_for_sale  CollectibleForSale
 * @var  $editable     boolean
 *
 * @var  $additional_multimedia iceModelMultimedia[]
 *
 * determine page height so we can display more/less sidebar widgets
 * @var  $height_main_div  stdClass
 *
 * this variable is set when the Collectible is part of aent Collection
 * @var  $brand  string
 */

  $height_main_div = new stdClass;
  $height_main_div->value = 92;
?>
<?php if (isset($first)): ?>
  <?php slot('prev_next'); ?>
    <link rel="prev" href="<?= url_for_collectible($previous) ?>">
    <link rel="next" href="<?= url_for_collectible($next) ?>">
    <link rel="start" href="<?= url_for_collectible($first) ?>">
  <?php end_slot(); ?>
<?php endif; ?>

<?php
  // if the collectible is part of aent Collection
  if(isset($brand)):
?>
  <div class="banners-620 spacer-bottom-20">
    <?php
      if ($brand === 'American Pickers')
      {
        echo link_to(image_tag('headlines/2012-0420_AP_Promo_Space_620x67_FIN.jpg'), '@aetn_american_pickers');
      }
      else if ($brand === 'Pawn Stars')
      {
        echo link_to(image_tag('headlines/2012-0420_PS_Promo_Space_620x67_FIN.jpg'), '@aetn_pawn_stars');
      }
      $height_main_div->value += 87;
    ?>
  </div>
<?php endif; ?>

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

    <?php if (isset($previous)): ?>
    <a href="<?= url_for_collectible($previous) ?>"
       class="prev-collectible" title="Previous: <?= $previous->getName(); ?>">
      <span>prev</span>
      <i class="icon-caret-left white"></i>
    </a>
    <?php endif; ?>

    <?php if (isset($next)): ?>
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

  <?php
    /** @var $image iceModelMultimedia */
    if (($image = $collectible->getPrimaryImage()) && $image->fileExists('620x0'))
    {
      $height_main_div->value += 15 + $image->getImageHeight('620x0');
    }
    else
    {
      // default - 20 margin to top + 490 height of image itself
      $height_main_div->value += 15 + 490;
    }
  ?>

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
    <?= $description = $collectible->getDescription('html'); ?>
  </div>

  <?php
    /**
     * Calculate height of <div class="item-description">
     * We have around 100 symbols in a row
     */
    $description_rows = (integer) (strlen($description) / 100 + 1);

    // Approximately 2 <br> tags account for a new line
    $br_count = (integer) (substr_count($description, '<br') / 2);
    $height_main_div->value += 20 + 18 * ($br_count + $description_rows);
  ?>
<?php endif; ?>

<?php
  if (
    isset($collectible_for_sale) &&
    $collectible_for_sale->isForSale() &&
    $collectible_for_sale->hasActiveCredit()
  )
  {
    include_partial('collection/collectible_for_sale', array(
      'collectible_for_sale' => $collectible_for_sale,
      'collectible' => $collectible,
      'collector' => $collector
    ));

    $height_main_div->value += 350;
  }
  else
  {
    include_partial(
      'comments/comments',
      array(
        'for_object' => $collectible->getCollectible(),
        'height' => &$height_main_div
      )
    );
  }
?>

<?php
  // if the collectible is part of aent Collection
  if(isset($brand)):
?>
  <?php
    $link = link_to(
      'See all related collectibles &raquo;', '@marketplace',
      array('class' => 'text-v-middle link-align')
    );
    $link = null;

    cq_section_title('Showcase', $link);
  ?>

  <div class="row">
    <div id="collectibles" class="row-content">
      <?php
      if (!empty($related_collectibles))
      {
        /** @var $related_collectibles Collectible[] */
        foreach ($related_collectibles as $i => $collectible)
        {
          include_partial(
            'collection/collectible_grid_view_square_small',
            array('collectible' => $collectible, 'i' => $i)
          );
        }
      }
      else if (!empty($related_collections))
      {
        foreach ($related_collections as $i => $collection)
        {
          include_partial(
            'collection/collection_grid_view_square_small',
            array('collection' => $collection, 'i' => $i)
          );
        }
      }
      ?>
    </div>
  </div>
  <?php $height_main_div->value += 379; ?>
<?php endif; ?>

<?php
  // pass variables to the sidebar
  $sf_user->setFlash('height_main_div', $height_main_div, 'false', 'internal');
  $sf_user->setFlash('brand', $brand, 'false', 'internal');
?>

<script type="text/javascript">
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
