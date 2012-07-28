<?php
/**
 * @var $pager PropelModelPager
 */
?>

<?php slot('mycq_create_collection'); ?>
<a href="<?= url_for('@ajax_mycq?section=collection&page=create'); ?>"
   id="collection-create-html" class="span5 add-new-zone open-dialog"
   title="Create a new collection by clicking here">
      <span id="collection-create-icon" class="btn-upload-collectible spacer-top-40">
        <i class="icon-plus icon-white"></i>
      </span>
      <span id="collection-create-link" class="btn-upload-collectible-txt spacer-top-20">
        CREATE A NEW<br> COLLECTION
      </span>
</a>
<?php end_slot(); ?>

<?php foreach ($pager->getResults() as $i => $collection): ?>
  <div class="span5 collectible_grid_view_square link"
       data-collection-id="<?= $collection->getId(); ?>">
    <p>
      <a href="<?= url_for('mycq_collection_by_section', array('id' => $collection->getId(), 'section' => 'collectibles')) ?>" class="target">
        <?= cqStatic::reduceText($collection->getName(), 35, '[...]'); ?>
      </a>
    </p>
    <ul class="thumbnails">
      <?php
      $c = new Criteria();
      $c->setLimit(8);
      $collectibles = $collection->getCollectionCollectibles($c);

      for ($k = 0; $k < 9; $k++)
      {
        if (isset($collectibles[$k]))
        {
          echo '<li>';
          echo link_to(
            image_tag_collectible(
              $collectibles[$k], '75x75',
              array('width' => 62, 'height' => 62)
            ),
            url_for(
              'mycq_collection_by_section',
              array('id' => $collection->getid(), 'section' => 'collectibles')
            )
          );
          echo '</li>';
        }
        else
        {
          echo '<li><i class="icon icon-plus drop-zone" data-collection-id="'.  $collection->getId() .'"></i></li>';
        }
      }
      ?>
    </ul>
    <div class="hidden">
      <span class="btn-upload-collectible spacer-top-40">
        <i class="icon-plus icon-white"></i>
      </span>
      <span class="btn-upload-collectible-txt spacer-top-20">
        ADD ITEM
      </span>
    </div>
  </div>

<?php
  if (($pager->getPage() === 1 && $i === 2) || ($pager->count() === $i+1 && $pager->count() < 3))
  {
    include_slot('mycq_create_collection');
  }
?>

<?php endforeach;?>

<?php if ($pager->count() === 0): ?>
  <?php include_slot('mycq_create_collection'); ?>
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
          'id' => 'collections-pagination',
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

      $('#collections-pagination a').click(function(e)
      {
        e.preventDefault();
        var page = $(this).data('page');

        $('#collections').parent().showLoading();
        $('#collections').load(
          $url +'?p='+ page, $form.serialize(),
          function(data) {
            $('#collections').parent().hideLoading();
          }
        );

        // Scroll to #slot1 so that we can see the first row of results
        $.scrollTo('#slot1');

        return false;
      });
    });
  </script>

<?php endif; ?>

<script>
$(document).ready(function()
{
  $(document).controls();

  $("#collections .collectible_grid_view_square").droppable(
  {
    activeClass: 'ui-state-hover',
    over: function(event, ui)
    {
      $(this).addClass('dashed');
    },
    out: function(event, ui)
    {
      $(this).removeClass('dashed');
    },
    drop: function(event, ui)
    {
      $(this).removeClass('dashed');
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
    activeClass: 'ui-state-hover',
    over: function(event, ui)
    {
      $(this)
        .addClass('ui-state-highlight')
    },
    out: function(event, ui)
    {
      $(this)
        .removeClass('ui-state-highlight')
    },
    drop: function(event, ui)
    {
      $(this).removeClass('ui-state-highlight');

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
