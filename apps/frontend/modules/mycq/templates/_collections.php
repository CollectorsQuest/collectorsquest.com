<?php
/**
 * @var $pager PropelModelPager
 */
?>

<?php slot('mycq_create_collection'); ?>
<div id="collection-create-html" class="span4 thumbnail">
  <div class="row-fluid spacer-inner-top-15">
    <div class="span5">
      <a href="<?= url_for('@ajax_mycq?section=component&page=createCollection'); ?>"
         id="collection-create-icon" class="open-dialog btn-create-collection-middle spacer-left-20">
        <i class="icon-plus icon-white"></i>
      </a>
    </div>
    <div class="span7">
      <a href="<?= url_for('@ajax_mycq?section=component&page=createCollection'); ?>"
         id="collection-create-link" class="open-dialog create-collection-text">
        Create a new collection by clicking here
      </a>
    </div>
  </div>
</div>
<?php end_slot(); ?>

<?php foreach ($pager->getResults() as $i => $collection): ?>

  <div class="span4 thumbnail link">
    <span>
      <a href="<?= url_for('mycq_collection_by_slug', $collection) ?>" style="margin-left: 0px;" class="target">
        <?= cqStatic::reduceText($collection->getName(), 35, '[...]'); ?>
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
          echo '<i class="icon icon-plus drop-zone" data-collection-id="'.  $collection->getId() .'"></i>';
        }
      }
    ?>
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
      <span class="Chivo webfont info-no-collections-uploaded" style="padding-top: 15px;">
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

<?php if ($pager->haveToPaginate() && $pager->getPage() === 1): ?>
  <button class="btn btn-small see-more-full" id="seemore-mycq-collections">
    See more
  </button>

  <script>
    $(document).ready(function()
    {
      var $url = '<?= url_for('@ajax_mycq?section=component&page=collections', true) ?>';
      var $form = $('#form-mycq-collections');

      $('#seemore-mycq-collections').click(function()
      {
        var $button = $(this);
        $button.html('loading...');

        $.post($url +'?p=2', $form.serialize(), function(data)
        {
          $('div.mycq-collections .thumbnails').append(data);
          $button.hide();
        }, 'html');
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

      var href = $('#collection-create-link').attr('href');
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
