<div class="span6 brick">
  <div class="wide">
    <a href="<?= url_for('collection_by_slug', $collection); ?>" class="link-brick">
      <?= image_tag_collection($collection, '295x140'); ?>
    </a>
    <div class="details" style="word-wrap: break-word;">
      <h3><?= $collection->getName(); ?></h3><br/>
      <p><?= $collection->getDescription('stripped', 140); ?></p>
    </div>
  </div>
</div>
