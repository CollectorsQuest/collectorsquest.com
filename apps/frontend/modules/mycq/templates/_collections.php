<div class="mycq-collections">
  <div class="row thumbnails">
    <?php foreach ($collections as $collection): ?>
    <div class="span4 thumbnail link">
      <span>
        <?php
          echo link_to_collection(
            $collection, 'text',
            array('truncate' => 32, 'style' => 'margin-left: 0px;')
          );
        ?>
      </span>
      <?php
        $c = new Criteria();
        $c->setLimit(2);
        foreach ($collection->getCollectionCollectibles($c) as $collectible)
        {
          echo link_to_collectible(
            $collectible, 'image',
            array('width' => 75, 'height' => 75, 'max_width' => 64, 'max_height' => 64)
          );
        }
      ?>
      <i class="add-white-icon drop-zone"></i>
    </div>
    <?php endforeach; ?>
    <div class="span4 thumbnail link">
      <div class="row-fluid spacer-inner-top-15">
        <div class="span5">
          <i class="add-white-icon create-collection pull-right"></i>
        </div>
        <div class="span7">
          <a href="#" class="create-collection-text target">Create a new collection by clicking here</a>
        </div>
      </div>
    </div>
  </div>
</div>

<a href="#" class="btn btn-small gray-button see-more-button">
  See more
</a>
