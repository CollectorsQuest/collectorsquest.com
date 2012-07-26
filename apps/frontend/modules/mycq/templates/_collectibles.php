<?php
/**
 * @var $pager sfPropelPager
 */
?>

<?php slot('mycq_create_collectible'); ?>
<div class="span3 collectible_grid_view_square link">
  <div id="mycq-create-collectible" data-collection-id="<?= $collection->getId() ?>" class="add-new-zone">
    <a href="<?= url_for('@ajax_mycq?section=component&page=createCollectible&collection_id='. $collection->getId()); ?>"
       id="collectible-create-icon" class="open-dialog btn-upload-collectible">
      <i class="icon-plus icon-white"></i>
    </a>
    <a href="<?= url_for('@ajax_mycq?section=component&page=createCollectible&collection_id='. $collection->getId()); ?>"
       id="collectible-create-link" class="open-dialog btn-upload-collectible-txt">
      ADD NEW ITEM
    </a>
  </div>
</div>
<?php end_slot();?>

<?php if ($pager->getNbResults() > 0): ?>

  <?php foreach ($pager->getResults() as $i => $collectible): ?>
  <div class="span3 collectible_grid_view_square link">
    <?php
      echo link_to(
        image_tag_collectible(
          $collectible, '150x150', array('width' => 140, 'height' => 140)
        ),
        'mycq_collectible_by_slug', $collectible
      );
    ?>
    <p>
      <?php
        echo link_to(
          cqStatic::reduceText($collectible->getName(), 30), 'mycq_collectible_by_slug',
          $collectible, array('class' => 'target')
        );
      ?>
    </p>
  </div>

  <?php
    if (
      ($pager->getPage() === 1 && $i === 4) ||
      ($pager->count() === $i+1 && $pager->count() < 5)
    ) {
      include_slot('mycq_create_collectible');
    }
  ?>
  <?php endforeach; ?>

  <?php if ($pager->haveToPaginate()): ?>
  <br clear="all">
  <div class="row-fluid text-center">
    <?php
    include_component(
      'global', 'pagination',
      array(
        'pager' => $pager,
        'options' => array(
          'id' => 'collectibles-pagination',
          'show_all' => false
        )
      )
    );
    ?>
  </div>

  <script>
    $(document).ready(function()
    {
      var $url = '<?= url_for('@ajax_mycq?section=component&page=collectibles', true) ?>';
      var $form = $('#form-mycq-collectibles');

      $('#collectibles-pagination a').click(function(e)
      {
        e.preventDefault();
        var page = $(this).data('page');

        $('#collectibles').parent().showLoading();

        $('#collectibles').load(
          $url +'?p='+ page, $form.serialize(),
          function(data) {
            $('#collectibles').parent().hideLoading();
          }
        );

        return false;
      });
    });
  </script>

  <?php endif; ?>

<?php else: ?>

<?php include_slot('mycq_create_collectible'); ?>
<div class="span12 thumbnail link no-collections-uploaded-box">
  <span class="Chivo webfont info-no-collections-uploaded">
    Share your collectibles with the community today!<br/>
    Get Started Now!
  </span>
</div>

<?php endif; ?>

<script>
$(document).ready(function()
{
  $(document).controls();

  $("#mycq-create-collectible").droppable(
  {
    over: function(event, ui)
    {
      $(this)
        .addClass('ui-state-highlight')
        //.find('i')
        //.removeClass('icon-plus')
        //.addClass('icon-download-alt');
    },
    out: function(event, ui)
    {
      $(this)
        .removeClass('ui-state-highlight')
        //.find('i')
        //.removeClass('icon-download-alt')
        //.addClass('icon-plus');
    },
    drop: function(event, ui)
    {
      $(this)
        .removeClass('ui-state-highlight')
        //.find('i')
        //.removeClass('icon-download-alt')
        //.addClass('icon-plus');

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
