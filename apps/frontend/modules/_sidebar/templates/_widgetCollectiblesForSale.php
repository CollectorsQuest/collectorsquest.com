<?php
/**
 * @var  $title  string
 * @var  $collectibles_for_sale  CollectibleForSale[]
 * @var  $height  stdClass
 * @var  $limit  integer
 */

$_height = 0;
?>

<?php
  cq_sidebar_title(
    $title, cq_link_to(
      'Explore Market &raquo;',
      '@marketplace', array('class' => 'text-v-middle link-align')
    ),
    array('left' => 7, 'right' => 5)
  );

  $_height -= 63;
?>

<div id="items-for-sale-sidebar">
<?php foreach ($collectibles_for_sale as $i => $collectible_for_sale): ?>
  <div class="row-fluid">
    <div class="inner-border link">
      <div class="span3">
        <?php
          echo link_to_collectible($collectible_for_sale->getCollectible(), 'image', array(
            'image_tag' => array('width' => 75, 'height' => 75, 'max_width' => 60),
            'link_to' => array('class' => 'target')
          ));
        ?>
      </div>
      <div class="span9 fix-height-text-block">
        <div class="content-container">
          <?php
            echo link_to_collectible(
              $collectible_for_sale->getCollectible(), 'text', array('class' => 'target')
            );
          ?>
          <p>
            <?= $collectible_for_sale->getCollectible()->getDescription('stripped'); ?>
          </p>
          <span class="price">
            <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>
          </span>
        </div>
       </div>
    </div>
  </div>
  <?php $_height -= 85; ?>
<?php endforeach; ?>
</div>

<?php
  if (isset($height) && property_exists($height, 'value'))
  {
    $height->value -= abs($_height);
  }
?>
