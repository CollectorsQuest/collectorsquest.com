<?php
/**
 * @var $pager sfPropelPager
 */
?>

<?php slot('mycq_create_collectible'); ?>
<div class="span3 collectible_grid_view_square link add-new-holder">
  <div data-collection-id="<?= $collection->getId() ?>" class="add-new-zone mycq-create-collectible">
    <a href="<?= url_for('@mycq_collectible_create_wizard?collection_id='. $collection->getId()); ?>"
       id="collectible-create-icon" class="btn-upload-collectible">
      <i class="icon-plus icon-white"></i>
    </a>
    <a href="<?= url_for('@mycq_collectible_create_wizard?collection_id='. $collection->getId()); ?>"
       id="collectible-create-link" class="btn-upload-collectible-txt">
      ADD NEW ITEM<br>
      <span style="color: #999;">(a single item)</span>
    </a>
  </div>
</div>
<?php end_slot();?>

<?php if ($pager->getNbResults() > 0): ?>

  <?php
    foreach ($pager->getResults() as $i => $collectible)
    {
      include_partial(
        'mycq/collectible_grid_view',
        array('collectible' => $collectible, 'i' => $i)
      );

      if (
        ($pager->getPage() === 1 && $i === 4) ||
        ($pager->count() === $i+1 && $pager->count() < 5)
      ) {
        include_slot('mycq_create_collectible');
      }
    }
  ?>

  <?php if ($pager->haveToPaginate()): ?>
  <div class="row-fluid pagination-wrapper">
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

        // Scroll to #slot1 so that we can see the first row of results
        $.scrollTo('#slot1');

        return false;
      });
    });
  </script>

  <?php endif; ?>

<?php else: ?>

  <?php if ($sf_params->get('q')): ?>
    <div class="alert alert-no-results">
      <i class="icon-warning-sign"></i>&nbsp;
      None of your items match search term: <strong><?= $sf_params->get('q'); ?></strong>.
      Do you want to <?= link_to('see all items', 'mycq_collection_by_slug', $collection); ?> or
      <?= link_to('add a new item', '@mycq_collectible_create_wizard?collection_id='. $collection->getId()); ?>?
    </div>
  <?php else: ?>
    <?php include_slot('mycq_create_collectible'); ?>
    <div class="span12 thumbnail link no-collections-uploaded-box">
      <span class="Chivo webfont info-no-collections-uploaded spacer-top-20">
        Upload photos and drag them here to add to your collection.<br/>
        Get Started Now!
      </span>
      <div class="hidden">
        <div class="dropped-zone">
          <div class="btn-upload-collectible">
            <i class="icon-plus icon-white"></i>
          </div>
          <div class="btn-upload-collectible-txt">
            ADD NEW ITEM
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>

<?php endif; ?>

<script>
$(document).ready(function()
{
  $(document).controls();

  var collection_id = <?= $collection->getId(); ?>;

  // the Add new collectible button
  var $add_new = $('#mycq-tabs .collectible_grid_view_square.add-new-holder');
  // a virtual "add new collectible" dom node
  var $add_new_placeholder = $('<div id="add-new-collectible-placeholder" class="span3 collectible_grid_view_square link dashed">' +
      '<div id="mycq-create-collectible" class="add-new-zone ui-droppable ui-state-highlight ui-state-hover">' +
        '<div class="btn-upload-collectible">' +
          '<i class="icon-plus icon-white"></i>' +
        '</div>' +
        '<div class="btn-upload-collectible-txt">' +
          'ADD ITEM' +
        '</div>' +
      '</div>' +
  '</div>');

  // Add the placeholder when dragging a collectible from the dropbox over another
  // collectible (except the "add new collectible button")
  $("#mycq-tabs .collectible_grid_view_square:not(.add-new-holder)").droppable({
    addClasses: false,
    over: function(event, ui) {
      var $this = $(this);
          pos = $this.position();

      // if we are on the first row of collectibles hide the "add new collectible" button
      if (pos.top < 600) {
        $add_new.hide();
      }

      // and add the dom node before the current collectible
      $this.before($add_new_placeholder);
    },
    out: function(event, ui) {
      var $this = $(this)
          pos = $this.position();
      // remove the previously inserted dom node
      $('#add-new-collectible-placeholder').remove();

      // and if we had hidden the "add new collectible" button, show it again
      if (pos.top < 600) {
        $add_new.show();
      }
    }
  });

  $('#mycq-tabs .mycq-collectibles').droppable({
    drop: function(event, ui) {
      $(this).removeClass('ui-state-highlight');

      ui.draggable.draggable('option', 'revert', false);
      ui.draggable.hide();

      $(this).showLoading();

      var url = '<?= url_for('@mycq_collection_collectible_create') ?>';

      window.location.href = url +
        '?collection_id=' + collection_id +
        '&collectible_id=' + ui.draggable.data('collectible-id');
    }
  });

  $(".mycq-create-collectible").droppable(
  {
    over: function(event, ui)
    {
      $(this).addClass('ui-state-highlight');
    },
    out: function(event, ui)
    {
      $(this).removeClass('ui-state-highlight');
    },
    drop: function(event, ui)
    {
      $(this).removeClass('ui-state-highlight');
    }
  });

  $("#collectibles .span12.thumbnail").droppable({
    addClasses: false,
    over: function(event, ui)
    {
      $(this).addClass('large-box-no-items-green-dashed');
      $(this).removeClass('no-collections-uploaded-box');
    },
    out: function(event, ui)
    {
      $(this).removeClass('large-box-no-items-green-dashed');
      $(this).addClass('no-collections-uploaded-box');
    },
    drop: function(event, ui)
    {
      $(this).removeClass('large-box-no-items-green-dashed');
      $(this).addClass('no-collections-uploaded-box');
    }
  });
});
</script>
