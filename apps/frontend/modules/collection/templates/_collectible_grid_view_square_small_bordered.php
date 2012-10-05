<?php
/**
 * @var $collectible Collectible
 */
?>
<div class="span3 thumbnail link">
  <?php
    echo link_to_collectible($collectible, 'image', array(
      'image_tag' => array('width' => 150, 'height' => 150, 'max_width' => 132,
                           'max_height' => 132, 'class' => 'lazy')
    ));
  ?>
  <p>
    <?php
      echo link_to_collectible($collectible, 'text', array(
        'truncate' => 20,
        'link_to' => array('class' => 'target')
      ));
    ?>
  </p>
</div>
