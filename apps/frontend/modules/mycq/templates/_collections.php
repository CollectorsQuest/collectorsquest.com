<?php
/**
 * @var $pager PropelModelPager
 */
?>

<?php slot('mycq_create_collection'); ?>
<a href="<?= url_for('@ajax_mycq?section=component&page=createCollection'); ?>"
   id="collection-create-html" class="span5 add-new-zone open-dialog"
   title="Create a new collection by clicking here">
  <span id="collection-create-icon" class="btn-upload-collectible">
    <i class="icon-plus icon-white"></i>
  </span>
  <span id="collection-create-link" class="btn-upload-collectible-txt">
    Create a new<br> collection by<br> clicking here
  </span>
</a>
<?php end_slot(); ?>

<?php foreach ($pager->getResults() as $i => $collection): ?>

<div class="span5 collectible_grid_view_square link">
  <p>
    <a href="<?= url_for('mycq_collection_by_slug', $collection) ?>" class="target">
      <?= cqStatic::reduceText($collection->getName(), 35, '[...]'); ?>
    </a>
  </p>
  <ul class="thumbnails">
    <?php
    $c = new Criteria();
    $c->setLimit(2);
    $collectibles = $collection->getCollectionCollectibles($c);

    for ($k = 0; $k < 3; $k++)
    {
      if (isset($collectibles[$k]))
      {
        echo '<li>', link_to(image_tag_collectible(
          $collectibles[$k], '75x75',
          array('max_width' => 60, 'max_height' => 60,)
        ), url_for('mycq_collection_by_slug', $collection)), '</li>';
      }
      else
      {
        echo '<li><i class="icon icon-plus drop-zone" data-collection-id="'.  $collection->getId() .'"></i></li>';
      }
    }
    ?>
  </ul>
</div>

<?php
  if (($pager->getPage() === 1 && $i === 2) || ($pager->count() === $i+1 && $pager->count() < 3))
  {
    include_slot('mycq_create_collection');
  }
?>

<?php endforeach; ?>

<?php if ($pager->count() === 0): ?>
  <div class="span12 thumbnail link no-collections-uploaded-box">
    <?php if ($sf_params->get('q')): ?>
      <span class="Chivo webfont info-no-collections-uploaded spacer-top-15">
        None of your collections match search term: <strong><?= $sf_params->get('q'); ?></strong>
      </span>
    <?php else: ?>
      <span class="Chivo webfont info-no-collections-uploaded">
        Share your collections with the community today!<br/>
        Get Started Now!
      </span>
    <?php endif; ?>
  </div>
  <?php include_slot('mycq_create_collection'); ?>
<?php endif; ?>

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
      var $url = '<?= url_for('@ajax_mycq?section=component&page=collections', true) ?>';
      var $form = $('#form-mycq-collections');

      $('#collectibles-pagination a').click(function(e)
      {
        e.preventDefault();

        $('#collectibles').parent().showLoading();

        $('#collectibles').load(
          $url +'?p=2', $form.serialize(),
          function(data) {
            $('#collectibles').parent().hideLoading();
          }
        );

        return false;
      });
    });
  </script>

<?php endif; ?>

<script>
$(document).ready(function()
{
  $(document).controls();

  $(".mycq-collections .drop-zone").droppable(
  {
    over: function(event, ui)
    {
      $(this)
        .removeClass('icon-plus')
        .addClass('ui-state-highlight')
        .addClass('icon-download-alt');
    },
    out: function(event, ui)
    {
      $(this)
        .removeClass('ui-state-highlight')
        .removeClass('icon-download-alt')
        .addClass('icon-plus');
    },
    drop: function(event, ui)
    {
      $(this).removeClass('ui-state-highlight');
      ui.draggable.draggable('option', 'revert', false);
      ui.draggable.hide();

      $(this).showLoading();

      var url = '<?= url_for('@mycq_collection_collectible_create') ?>';

      window.location.href = url +
        '?collection_id=' + $(this).data('collection-id') +
        '&collectible_id=' + ui.draggable.data('collectible-id');
    }
  });

  $("#collection-create-html").droppable(
  {
    over: function(event, ui)
    {
      $(this)
        .addClass('ui-state-highlight')
        .find('i')
          .removeClass('icon-plus')
          .addClass('icon-download-alt');
    },
    out: function(event, ui)
    {
      $(this)
        .removeClass('ui-state-highlight')
        .find('i')
         .removeClass('icon-download-alt')
         .addClass('icon-plus');
    },
    drop: function(event, ui)
    {
      $(this)
        .removeClass('ui-state-highlight')
        .find('i')
          .removeClass('icon-download-alt')
          .addClass('icon-plus');

      ui.draggable.draggable('option', 'revert', true);

      var href = $(this).attr('href');
      href = href +'?collectible_id=' + ui.draggable.data('collectible-id');

      var options = {
        modal: true,
        autOpen: true,
        content: href
      };

      $("<div></div>").dialog2(options);
    }
  });
});
</script>
