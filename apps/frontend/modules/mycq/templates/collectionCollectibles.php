<?php
/**
 * @var $collection CollectorCollection
 * @var $form CollectorCollectionEditForm
 */
?>

<?php
  cq_sidebar_title(
    $collection->getName(), null,
    array('left' => 10, 'right' => 2, 'class'=>'spacer-top-reset row-fluid sidebar-title')
  );
?>

<?php include_partial('mycq/collection_blue_bar', array('collection' => $collection)); ?>

<div id="mycq-tabs">
  <ul class="nav nav-tabs">
    <li class="active">
      <?php
        echo link_to(
          'Collectibles ('. $total .')',
          'mycq_collection_by_section', array(
            'id' => $collection->getId(),
            'section' => 'collectibles'
          )
        );
      ?>
    </li>
    <li>
      <?php
        echo link_to(
          'Collection Details',
          'mycq_collection_by_section', array(
            'id' => $collection->getId(),
            'section' => 'details'
          )
        );
      ?>
    </li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active">
      <div class="tab-content-inner spacer-inner-top-reset">

        <div class="row mycq-collectibles">
          <div class="row-content" id="collectibles">
            <?php include_component('mycq', 'collectibles', array('collection' => $collection)); ?>
          </div>
        </div>

        <div class="pagination reset-t-b-margin">
          <ul>
            <li class="active">
              <a href="#">1</a>
            </li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li><a href="#">4</a></li>
            <li><a href="#">Next</a></li>
          </ul>
        </div>

      </div><!-- .tab-content-inner -->
    </div> <!-- .tab-pane.active -->
    <div id="tab4" class="tab-pane">
      <div class="tab-content-inner spacer">

      </div><!-- .tab-content-inner -->
    </div><!-- #tab4.tab-pane -->
  </div><!-- .tab-content -->
</div>

<script>
$(document).ready(function()
{
  $('#collection_description').wysihtml5({
    "font-styles": false, "image": false, "link": false,
    events:
    {
      "load": function() {
        $('#collection_description')
          .removeClass('js-hide')
          .removeClass('js-invisible');
      },
      "focus": function() {
        $(editor.composer.iframe).autoResize();
      }
    }
  });

  $('.add-new-zone').on('mouseenter', function() {
    var $this = $(this);
    $this.find('i.icon-plus')
      .removeClass('icon-plus')
      .addClass('icon-hand-up')
      .show();
  });
  $('.add-new-zone').on('mouseleave', function() {
    var $this = $(this);
    $this.find('i.icon-hand-up')
      .removeClass('icon-hand-up')
      .addClass('icon-plus')
      .show();
  });

  $("#form-collection .drop-zone-large").droppable(
  {
    over: function(event, ui)
    {
      $(this).addClass('ui-state-highlight');
      $(this).find('.icon-plus-holder i')
        .removeClass('icon-plus')
        .addClass('icon-download-alt');
      $(this).find('img').hide();
      $(this).find('.holder-icon-edit').hide();
      $(this).find('span.icon-plus-holder').show();
    },
    out: function(event, ui)
    {
      $(this).removeClass('ui-state-highlight');
      $(this).find('.icon-plus-holder i')
        .removeClass('icon-download-alt')
        .addClass('icon-plus');
      $(this).find('span.icon-plus-holder').hide();
      $(this).find('.holder-icon-edit').show();
      $(this).find('img').show();
    },
    drop: function(event, ui)
    {
      $(this).removeClass('ui-state-highlight');
      $(this).find('.holder-icon-edit i')
        .removeClass('icon-download-alt')
        .addClass('icon-plus');
      ui.draggable.draggable('option', 'revert', false);

      $(this).showLoading();

      $.ajax({
        url: '<?= url_for('@ajax_mycq?section=collection&page=setThumbnail'); ?>',
        type: 'GET',
        data: {
          collectible_id: ui.draggable.data('collectible-id'),
          collection_id: '<?= $collection->getId() ?>'
        },
        success: function()
        {
          window.location.reload();
        },
        error: function()
        {
          // error
        }
      });
    }
  });
});
</script>

<script>
$(document).ready(function()
{
  $('.dropdown-menu a.sortBy').click(function()
  {
    $('#sortByName').html($(this).data('name'));
    $('#sortByValue').val($(this).data('sort'));

    $('#form-mycq-collectibles').submit();
  });

  var $url = '<?= url_for('@ajax_mycq?section=component&page=collectibles', true) ?>';
  var $form = $('#form-mycq-collectibles');

  $form.submit(function()
  {
    $('div.mycq-collections .thumbnails').fadeOut();

    $.post($url +'?p=1', $form.serialize(), function(data)
    {
      $('div.mycq-collections .thumbnails').html(data).fadeIn();
    },'html');

    return false;
  });
});
</script>
