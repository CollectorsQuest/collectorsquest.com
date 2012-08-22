<?php
/**
 * @var $collection Collection
 * @var $i integer
 */
?>

<div id="collection_<?= $collection->getId(); ?>_grid_view"
     data-id="<?= $collection->getId(); ?>"
     class="collection_grid_view fade-white link">

  <div class="mosaic-overlay">
    <p class="details">
      <?php
        echo link_to_collection($collection, 'text', array(
          'link_to' => array('class' => 'target', 'style' => 'font-size: 130%;')
        ));
      ?>
      <br/><small>by</small>
      <?php
        echo link_to_collector($collection->getCollector(), 'text', array(
          'link_to' => array('style' => 'color: #000;')
        ));
      ?>
    </p>
  </div>
  <?php
    echo link_to_collection($collection, 'image', array(
        'image_tag' => array( 'width' => 190, 'height' => 150),
        'link_to' => array('class' => 'mosaic-backdrop')
    ));
  ?>
</div>
