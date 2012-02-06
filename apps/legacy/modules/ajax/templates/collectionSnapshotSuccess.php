<center>
<?php
  foreach($collectibles as $i => $collectible)
  {
    echo link_to_collectible($collectible, 'image', array('width' => 75, 'height' => 75, 'style' => 'margin: 0 1px;'));
  }
?>
</center>