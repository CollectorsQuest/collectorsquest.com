<?php
/**
 * @var $pager sfPropelPager
 */
?>

<?php slot('mycq_create_collectible'); ?>
<div class="span4 thumbnail link">
  <div class="row-fluid spacer-inner-top-15">
    <div class="span5">
      <a href="<?php echo url_for('@ajax_mycq?section=component&page=createCollectible&collection_id='. $collection->getId()); ?>"
         class="open-dialog btn-create-collection-middle spacer-left-20">
        <i class="icon-plus icon-white"></i>
      </a>
    </div>
    <div class="span7">
      <a href="<?php echo url_for('@ajax_mycq?section=component&page=createCollectible&collection_id='. $collection->getId()); ?>"
         class="open-dialog create-collection-text">
        Create a new Collectible by clicking here
      </a>
    </div>
  </div>
</div>
<?php end_slot();?>

<?php if ($pager->getNbResults() > 0): ?>

  <div class="mycq-collections">
    <div class="row thumbnails">
      <?php foreach ($pager->getResults() as $i => $collectible): ?>
      <?php
        if ($pager->getPage() === 1 && $i == 3)
        {
          include_slot('mycq_create_collectible');
        }
      ?>
      <div class="span4 thumbnail link">
        <span>
          <a href="<?= url_for('mycq_collectible_by_slug', $collectible) ?>" style="margin-left: 0px;" class="target">
            <?= Utf8::truncateHtmlKeepWordsWhole($collectible->getName(), 32); ?>
          </a>
        </span>
        <?php
          $q = iceModelMultimediaQuery::create()
            ->filterByModel('Collectible')
            ->filterByModelId($collectible->getId())
            ->orderByIsPrimary(Criteria::DESC)
            ->orderByCreatedAt(Criteria::DESC);
          $multimedia = $q->limit(2)->find();

          for ($i = 0; $i < 3; $i++)
          {
            if (isset($multimedia[$i]))
            {
              echo link_to(image_tag_multimedia(
                $multimedia[$i], '75x75',
                array('max_width' => 64, 'max_height' => 64,)
              ), url_for('mycq_collectible_by_slug', $collectible));
            }
            else
            {
              echo '<i class="icon icon-download-alt drop-zone" data-collectible-id="'.  $collectible->getId() .'"></i>';
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

  <div class="mycq-collections spacer-top-25">
    <div class="row thumbnails">
      <div class="span12 thumbnail link no-collections-uploaded-box">
        <span class="Chivo webfont info-no-collections-uploaded">
          Share your collectibles with the community today!<br>
          Upload then sort your collectibles to get started.
        </span>
      </div>
      <?php include_slot('mycq_create_collectible'); ?>
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
      ui.draggable.draggable( 'option', 'revert', false );

      $.ajax({
        url: '<?php echo url_for('@ajax_mycq?section=collectible&page=donateImage'); ?>',
        type: 'GET',
        data: {
          donor_id: ui.draggable.data('collectible-id'),
          recipient_id: $(this).data('collectible-id')
        },
        success: function()
        {
          ui.draggable.draggable('option', 'revert', false);
          ui.draggable.hide();
        },
        error: function()
        {
          ui.draggable.draggable('option', 'revert', true);
          ui.draggable.show();
        }
      });
    }
  });
});
</script>
