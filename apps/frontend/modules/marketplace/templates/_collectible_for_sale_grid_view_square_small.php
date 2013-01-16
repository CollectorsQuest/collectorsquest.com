<?php
  /* @var $collectible_for_sale CollectibleForSale */
  /* @var $link_parameters array */
$default_link_parameters = array(
    'title' => $collectible_for_sale->getName(),
);
$link_parameters = isset($link_parameters)
  ? array_merge($default_link_parameters, $link_parameters)
  : $default_link_parameters;
?>

<div class="span3 thumbnail link">
  <?php
    echo link_to_collectible($collectible_for_sale->getCollectible(), 'image', array(
      'image_tag' => array('width' => 150, 'height' => 150, 'max_width' => 132, 'max_height' => 132),
      'link_to' => $link_parameters,
    ));
  ?>
  <p>
    <?php
      echo link_to_collectible(
        $collectible_for_sale->getCollectible(),
        'text',
        array(
            'class' => 'target',
            'link_to' => $link_parameters,
        )
      );
    ?>
  </p>
  <span>
    <?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?>
  </span>
</div>
