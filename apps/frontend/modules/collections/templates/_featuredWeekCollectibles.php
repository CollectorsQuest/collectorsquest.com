<div class="row">
  <?php
    /** @var $collectibles Collectible[] */
    foreach ($collectibles as $i => $collectible):
  ?>
    <div class="span3 expanded-images-inner">
      <a href="<?= url_for_collectible($collectible); ?>">
        <?= ice_image_tag_flickholdr('140x140', array('i' => $i)); ?>
        <?php image_tag_collectible($collectible); ?>
      </a>
    </div>
  <?php endforeach; ?>
</div>
