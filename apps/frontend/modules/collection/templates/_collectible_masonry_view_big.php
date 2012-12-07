<?php
/**
 * @var $collectible          Collectible
 * @var $url                  string
 * @var $link_parameters      string
 */
?>

<div id="collectible_<?= $collectible->getId(); ?>_grid_view_masonry_big"
     data-id="<?= $collectible->getId(); ?>"
     class="span4 collectible_for_sale_grid_view_masonry_big brick link">

  <?php $default_url = url_for_collectible($collectible); ?>

  <a href="<?= $url ?: $default_url ?>" <?= _tag_options($link_parameters ?: array('class' => 'target')); ?>>
    <?= image_tag_collectible($collectible, '220x0'); ?>
  </a>

  <div class="collectible-info">
    <a href="<?= $url ?: $default_url ?>" <?= _tag_options($link_parameters ?: array('class' => 'target')); ?>>
      <?= $collectible->getName(); ?><br/>
    </a>
  </div>

</div>
