<?php
/**
 * @var $pager sfPropelPager
 */
?>

<?php slot('mycq_create_collectible'); ?>
<div id="mycq-create-collectible" data-collection-id="<?= $collection->getId() ?>"
     class="span4 thumbnail link">
  <div class="row-fluid spacer-inner-top-15">
    <div class="span4">
      <a href="<?php echo url_for('@ajax_mycq?section=component&page=createCollectible&collection_id='. $collection->getId()); ?>"
         id="collectible-create-icon" class="open-dialog btn-create-collection-middle spacer-left-20">
        <i class="icon-plus icon-white"></i>
      </a>
    </div>
    <div class="span8">
      <a href="<?php echo url_for('@ajax_mycq?section=component&page=createCollectible&collection_id='. $collection->getId()); ?>"
         id="collectible-create-link" class="open-dialog create-collection-text">
        Add a new item to your collection by dragging it here.
      </a>
    </div>
  </div>
</div>
<?php end_slot();?>

<?php if ($pager->getNbResults() > 0): ?>

  <div class="mycq-collections">
    <div class="row thumbnails">
      <?php foreach ($pager->getResults() as $i => $collectible): ?>
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

          for ($k = 0; $k < 3; $k++)
          {
            if (isset($multimedia[$k]))
            {
              echo link_to(image_tag_multimedia(
                $multimedia[$k], '75x75',
                array('max_width' => 64, 'max_height' => 64,)
              ), url_for('mycq_collectible_by_slug', $collectible));
            }
            else
            {
              echo '<i class="icon icon-plus drop-zone" data-collectible-id="'.  $collectible->getId() .'"></i>';
            }
          }
        ?>
      </div>
      <?php
        if (($pager->getPage() === 1 && $i === 2) || ($pager->count() === $i+1 && $pager->count() < 3))
        {
          include_slot('mycq_create_collectible');
        }
      ?>
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
          Share your collection with the community today!<br/>
          Get Started Now!
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
      $(this)
        .addClass("ui-state-highlight")
        .removeClass('icon-plus')
        .addClass('icon-download-alt');
    },
    out: function(event, ui)
    {
      $(this)
        .removeClass("ui-state-highlight")
        .removeClass('icon-download-alt')
        .addClass('icon-plus');
    },
    drop: function(event, ui)
    {
      $(this)
        .removeClass("ui-state-highlight")
        .removeClass('icon-download-alt')
        .addClass('icon-plus');
      ui.draggable.draggable('option', 'revert', false);

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

  $("#mycq-create-collectible").droppable(
  {
    over: function(event, ui)
    {
      $(this)
        .addClass("ui-state-highlight")
        .find('i')
          .removeClass('icon-plus')
          .addClass('icon-download-alt');
    },
    out: function(event, ui)
    {
      $(this)
        .removeClass("ui-state-highlight")
        .find('i')
          .removeClass('icon-download-alt')
          .addClass('icon-plus');
    },
    drop: function(event, ui)
    {
      $(this)
        .removeClass("ui-state-highlight")
        .find('i')
          .removeClass('icon-download-alt')
          .addClass('icon-plus');

      ui.draggable.draggable('option', 'revert', false);
      ui.draggable.hide();

      $(this).showLoading();

      var url = '<?= url_for('@mycq_collection_collectible_create') ?>';

      window.location.href = url +
        '?collection_id=' + $(this).data('collection-id') +
        '&collectible_id=' + ui.draggable.data('collectible-id');
    }
  });
});
</script>
