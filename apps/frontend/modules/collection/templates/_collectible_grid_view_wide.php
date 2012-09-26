<?php
/**
 * @var $collectible Collectible
 */
?>

<div id="collectible_<?= $collectible->getId(); ?>_grid_view_wide"
     data-id="<?= $collectible->getId(); ?>"
     class="span6 collectible_grid_view_wide fade-white link">

  <div class="mosaic-overlay">
    <p class="details">
      <?php if (isset($open_dialog)): ?>
        <?php
          echo link_to($collectible->getName(), 'ajax_aetn',
            array(
              'section' => 'mwbaCollectible',
              'page' => 'show',
              'id' => $collectible->getId()
            ),
            array('class' => 'open-dialog', 'onclick' => 'return false;')
          );
        ?>
      <?php else: ?>
        <?= link_to_collectible($collectible, 'text', array('class' => 'target', 'truncate' => 40)); ?>
      <?php endif; ?>
    </p>
  </div>

  <?php
    echo link_to_collectible($collectible, 'image', array(
      'image_tag' => array('width' => 295, 'height' => 140),
      'link_to' => array('class' => 'mosaic-backdrop')
    ));
  ?>
</div>
