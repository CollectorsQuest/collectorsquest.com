<?php
/**
 * @var $pager PropelModelPager
 */
?>

<?php slot('html-create-collection'); ?>
<div class="span4 thumbnail link">
  <div class="row-fluid spacer-inner-top-15">
    <div class="span5">
      <a href="<?php echo url_for('@ajax_mycq?section=component&page=createCollection'); ?>" class="open-dialog btn-create-collection-middle spacer-left-20">
        <i class="icon-plus icon-white"></i>
      </a>
    </div>
    <div class="span7">
      <a href="<?php echo url_for('@ajax_mycq?section=component&page=createCollection'); ?>" class="open-dialog create-collection-text">
        Create a new collection by clicking here
      </a>
    </div>
  </div>
</div>
<?php end_slot();?>

<?php if ($pager->getNbResults() > 0): ?>

  <div class="mycq-collections">
    <div class="row thumbnails">
      <?php foreach ($pager->getResults() as $i => $collection): ?>
      <div class="span4 thumbnail link">
        <span>
          <a href="<?= url_for('mycq_collection_by_slug', $collection) ?>" style="margin-left: 0px;" class="target">
            <?= Utf8::truncateHtmlKeepWordsWhole($collection->getName(), 32); ?>
          </a>
        </span>
        <?php
          $c = new Criteria();
          $c->setLimit(2);
          $collectibles = $collection->getCollectionCollectibles($c);

          for ($k = 0; $k < 3; $k++)
          {
            if (isset($collectibles[$k]))
            {
              echo link_to(image_tag_collectible(
                $collectibles[$k], '75x75',
                array('max_width' => 64, 'max_height' => 64,)
              ), url_for('mycq_collection_by_slug', $collection));
            }
            else
            {
              echo '<i class="icon icon-download-alt drop-zone" data-collection-id="'.  $collection->getId() .'"></i>';
            }
          }
        ?>
      </div>
      <?php
        if (($pager->getPage() === 1 && $i === 2) || ($pager->count() === $i+1 && $pager->count() < 3))
        {
          include_slot('html-create-collection');
        }
      ?>
<<<<<<< Updated upstream
      <?php endforeach; ?>
=======
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
          <a href="#addNewCollection" data-toggle="modal" class="create-collection-text target">
            Create a new collection by clicking here
          </a>
        </div>
      </div>
>>>>>>> Stashed changes
    </div>
  </div>

<<<<<<< Updated upstream
  <?php if ($pager->haveToPaginate()): ?>
  <a href="#" class="btn btn-small gray-button see-more-button">
    See more
  </a>
  <?php endif; ?>
=======
<div class="modal" id="addNewCollection">
  <div class="modal-header">
    <button class="close" data-dismiss="modal">×</button>
    <h3>Add a new collection</h3>
  </div>
  <div class="modal-body">
    <form class="form-horizontal form-modal">
      <fieldset>
        <div class="control-group">
          <label class="control-label" for="input01">Collection Name</label>
          <div class="controls">
            <input type="text" class="input-xlarge" id="input01">
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input01">Tags</label>
          <div class="controls">
            <input type="text" class="input-xlarge" id="input02">
            <p class="help-block">Choose at least three descriptive words for your collection, separated by commas</p>
          </div>
        </div>
        <div class="control-group">
          <label for="select01" class="control-label">Category</label>
          <div class="controls">
            <select id="select01">
              <option>Please Select One:</option>
              <option>Category 1</option>
              <option>Category 2</option>
              <option>Category 3</option>
              <option>Category 4</option>
            </select>
          </div>
        </div>
      </fieldset>
    </form>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn btn-primary blue-button spacer-right-15">Create Collection</a>
    <a href="#" class="btn btn-primary gray-button">Cancel</a>
  </div>
</div>

<script>
  $(document).ready(function()
  {
    $('#addNewCollection').modal();
  });
</script>

<?php if ($pager->haveToPaginate()): ?>
<a href="#" class="btn btn-small gray-button see-more-button">
  See more
</a>
<?php endif; ?>
>>>>>>> Stashed changes

<?php else: ?>

  <div class="spacer-top-25">
    <div class="mycq-collections">
      <div class="row thumbnails">
        <div class="span12 thumbnail link no-collections-uploaded-box">
          <span class="Chivo webfont info-no-collections-uploaded">
            Share your collection with the community today!<br>
            Upload then sort your collectibles to get started.
          </span>
        </div>
        <?php include_slot('html-create-collection'); ?>
      </div>
    </div>
  </div>

<?php endif; ?>

<script>
$(document).ready(function()
{
  $(".mycq-collections .drop-zone").droppable(
  {
    over: function(event, ui)
    {
      $(this).addClass("ui-state-highlight");
    },
    out: function(event, ui)
    {
      $(this).removeClass("ui-state-highlight");
    },
    drop: function(event, ui)
    {
      $(this).removeClass("ui-state-highlight");
      ui.draggable.draggable('option', 'revert', false);

      $(this).showLoading();

      var url = '<?= url_for('@mycq_collection_collectible_create') ?>';

      window.location.href = url +
        '?collection_id=' + $(this).data('collection-id') +
        '&collectible_id=' + ui.draggable.data('collectible-id');
    }
  });
});
</script>
