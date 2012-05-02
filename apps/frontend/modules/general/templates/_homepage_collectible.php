<div class="span3 brick">
  <div class="square">
    <a href="<?= url_for_collectible($collectible); ?>" class="link-brick">
      <?= ice_image_tag_flickholdr('140x140', array('i' => $collectible->getId())) ?>
    </a>
    <div class="details" style="word-wrap: break-word;">
      <h3 class="details-inner"><?= $collectible->getName(); ?></h3>
    </div>
  </div>
</div>
