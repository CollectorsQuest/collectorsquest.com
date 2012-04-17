<div class="row" style="margin-left: -7px; margin-top: 20px; margin-bottom: -20px;">
  <div class="row-content">
  <?php
    /** @var $collectibles Collectible[] */
    foreach ($collectibles as $i => $collectible):
  ?>
    <div class="span4" style="margin: 0 0 20px 7px;">
      <a href="<?= url_for_collectible($collectible); ?>">
        <?= ice_image_tag_flickholdr('190x150', array('i' => $i)); ?>
        <?php image_tag_collectible($collectible); ?>
      </a>
    </div>
  <?php endforeach; ?>
  </div>
</div>
