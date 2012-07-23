<div class="span3 brick">
  <div class="square">
    <a href="<?= url_for_collectible($collectible); ?>" class="link-brick">
      <?= image_tag_collectible($collectible, '140x140'); ?>
    </a>
    <div class="details text-word-wrap">
      <h3 class="details-inner"><?= $collectible->getName(); ?></h3>
    </div>
  </div>
</div>
