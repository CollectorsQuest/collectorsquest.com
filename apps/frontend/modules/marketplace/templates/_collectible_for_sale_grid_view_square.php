<?php
  /* @var $collectible_for_sale CollectibleForSale */
  /* @var $link_parameters array */
  $default_link_parameters = array(
  );
  $link_parameters = isset($link_parameters)
    ? array_merge($default_link_parameters, $link_parameters)
    : $default_link_parameters;
?>

<div data-id="<?= $collectible_for_sale->getCollectorId(); ?>"
     class="span4 collectible_for_sale_grid_view_square link">

  <?php
    echo link_to_collectible($collectible_for_sale->getCollectible(), 'image', array(
      'image_tag' => array('width' => 190, 'height' => 190),
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
            'truncate' => 18,
            'link_to' => $link_parameters,
        )
      );
    ?>
    <strong class="pull-right"><?= money_format('%.2n', (float) $collectible_for_sale->getPrice()); ?></strong>
  </p>
</div>
