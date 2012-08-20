<?php
/**
 * @var $collectible Collectible
 */
?>
<div class="span3 thumbnail link">
  <?=
    link_to_collectible($collectible, 'image', array(
      'link_to' => array('width' => '', 'height' => '', 'alt' => ''),
      'image_tag' => array('width' => 150, 'height' => 150, 'max_width' => 132, 'max_height' => 132)
    ));
  ?>
  <p>
    <?=
      link_to_collectible($collectible, 'text',
        array('class' => 'target', 'truncate' => 20)
      );
    ?>
  </p>
</div>
