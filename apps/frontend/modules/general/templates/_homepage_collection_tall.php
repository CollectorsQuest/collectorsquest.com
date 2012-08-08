<div class="span3 brick">
  <div class="tall">
    <a href="<?= url_for('collection_by_slug', $collection, true); ?>" class="link-brick">
      <?= image_tag_collection($collection, '140x295'); ?>
    </a>
    <div class="details text-word-wrap">
      <h3><?= $collection->getName(); ?></h3><br/>
      <p><?= $collection->getDescription('stripped', 140); ?></p>
    </div>
  </div>
</div>
