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
  <button class="btn btn-small gray-button see-less-full"
          id="seeless-featured-week"
          data-url="<?= url_for('@ajax_collections?section=component&page=featuredWeekCollectibles') ?>"
          data-target="#weeks-promo-box div.imageset">
    See less
  </button>
</div>
