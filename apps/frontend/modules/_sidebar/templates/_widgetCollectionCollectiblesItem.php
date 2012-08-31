<?php /** @var $item Collectible*/ ?>
<a href="<?= url_for_collectible($item) ?>" class="thumbnail">
  <?php  echo image_tag_collectible($item, '100x100', array('width' => 90, 'height' => 90) ); ?>
</a>
