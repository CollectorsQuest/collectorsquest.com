<?php
/**
 * @var $pager sfPropelPager
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
      <?php
        if ($pager->getPage() === 1 && $i == 3)
        {
          include_slot('html-create-collection');
        }
      ?>
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

          for ($i = 0; $i < 3; $i++)
          {
            if (isset($collectibles[$i]))
            {
              echo image_tag_collectible(
                $collectibles[$i], '75x75',
                array('max_width' => 64, 'max_height' => 64)
              );
            }
            else
            {
              echo '<i class="add-white-icon drop-zone"></i>';
            }
          }
        ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <?php if ($pager->haveToPaginate()): ?>
  <a href="#" class="btn btn-small gray-button see-more-button">
    See more
  </a>
  <?php endif; ?>

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
