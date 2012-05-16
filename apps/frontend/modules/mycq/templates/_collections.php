<?php if ($pager->getNbResults() > 0): ?>
<div class="mycq-collections">
  <div class="row thumbnails">
    <?php foreach ($pager->getResults() as $collection): ?>
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
          <a href="#" class="btn-create-collection-middle spacer-left-20">
            <i class="icon-plus icon-white"></i>
          </a>
        </div>
        <div class="span7">
          <a href="#" class="create-collection-text target">Create a new collection by clicking here</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php if ($pager->haveToPaginate()): ?>
<a href="#" class="btn btn-small gray-button see-more-button">
  See more
</a>
<?php endif; ?>

<?php else: ?>

<div class="spacer-top-25">
  <!-- No Collection Uploaded -->

  <div class="mycq-collections">
    <div class="row thumbnails">
      <div class="span12 thumbnail link no-collections-uploaded-box">
        <span class="Chivo webfont info-no-collections-uploaded">
          Share your collection with the community today!<br>
          Upload then sort your collectibles to get started.
        </span>
      </div>
      <div class="span4 thumbnail link">
        <div class="row-fluid spacer-inner-top-15">
          <div class="span5">
            <a href="#" class="btn-create-collection-middle spacer-left-20">
              <i class="icon-plus icon-white"></i>
            </a>
          </div>
          <div class="span7">
            <a href="#addNewCollection" data-toggle="modal" class="create-collection-text target">
              Create a new collection by clicking here
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- /No Collection Uploaded -->
</div>

<?php endif; ?>

