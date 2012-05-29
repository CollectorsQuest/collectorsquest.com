<?php
/**
 * @var $collection CollectorCollection
 * @var $form CollectorCollectionEditForm
 */
?>

<form action="<?= url_for('mycq_collection_by_slug', $collection); ?>"
      id="form-collection" method="post" enctype="multipart/form-data"
      class="form-horizontal spacer-bottom-reset">
<div class="row-fluid">
  <div class="span3">
    <div class="drop-zone-large">
      <?php if ($collection->hasThumbnail()): ?>
        <?= image_tag_collection($collection, '190x190'); ?>
        <span class="plus-icon-holder h-center" style="display: none; padding-top: 25px;">
          <i class="icon icon-download-alt icon-white"></i>
        </span>
      <?php else: ?>
        <a class="plus-icon-holder h-center" href="#">
          <i class="icon icon-plus icon-white"></i>
        </a>
        <a class="blue-link" href="#">
          Click to Add Main<br>Thumbnail or Drag and Drop<br>from "Items to Sort" below
        </a>
      <?php endif; ?>
    </div>
  </div>
  <div class="span9">
    <?php
      $link = link_to(
        'Back to Collections &raquo;', '@mycq_collections',
        array('class' => 'text-v-middle link-align')
      );

      cq_sidebar_title(
        $collection->getName(), $link,
        array('left' => 8, 'right' => 4, 'class'=>'spacer-top-reset row-fluid sidebar-title')
      );
    ?>

    <fieldset>
        <?= $form; ?>
    </fieldset>

  </div>
  <div class="row-fluid">
    <div class="span12">
      <div class="form-actions text-center">
        <a href="<?= url_for('mycq_collection_by_slug', array('sf_subject' => $collection, 'cmd' => 'delete', 'encrypt' => '1')); ?>"
           class="btn gray-button spacer-left pull-left spacer-left"
           onclick="return confirm('Are you sure you want to delete this Collection?');">
          <i class="icon icon-trash"></i>
          Delete Collection
        </a>

        <button type="submit" class="btn btn-primary blue-button">Save Changes</button>
        <a href="<?= url_for('mycq_collection_by_slug', $collection) ?>"
           class="btn gray-button spacer-left">
          Cancel
        </a>
      </div>
    </div>
  </div>
</div>
</form>

<br/>
<div id="mycq-tabs">
  <div class="tab-content">
    <div class="tab-pane active" id="tab1">
      <?php
        include_component(
          'mycq', 'dropbox',
          array('instructions' => array(
            'position' => 'bottom',
            'text' => 'Drag and Drop Your Photos Below'
          ))
        );
      ?>

      <?php if ($total > 0 || $dropbox_total > 0): ?>
      <br style="clear: both;"/>
      <div class="tab-content-inner spacer-top-35">
        <div class="row-fluid sidebar-title spacer-inner-bottom">
          <div class="span5 link-align">
            <h3 class="Chivo webfont">Collectibles (<?= $total ?>)</h3>
          </div>
          <div class="span7">
            <?php if ($total > 11): ?>
            <div class="mycq-sort-search-box">
              <div class="input-append">
                <form id="form-mycq-collectibles" method="post"
                      action="<?= url_for('@ajax_mycq?section=component&page=collectibles') ?>">
                  <div class="btn-group">
                    <div class="append-left-gray">Sort by <strong id="sortByName">Position</strong></div>
                    <a class="btn gray-button dropdown-toggle" data-toggle="dropdown" href="#">
                      <span class="caret arrow-up"></span><br><span class="caret arrow-down"></span>
                    </a>
                    <ul class="dropdown-menu">
                      <li><a data-sort="position" data-name="Position" class="sortBy" href="javascript:">Sort by <strong>Position</strong></a></li>
                      <li><a data-sort="most-relevant" data-name="Most Relevant" class="sortBy" href="javascript:">Sort by <strong>Most Relevant</strong></a></li>
                      <li><a data-sort="most-recent" data-name="Most Recent" class="sortBy" href="javascript:">Sort by <strong>Most Recent</strong></a></li>
                    </ul>
                  </div>
                  <input type="text" class="input-sort-by" id="appendedPrependedInput" name="q"><button class="btn gray-button" type="submit"><strong>Search</strong></button>
                  <input type="hidden" value="position" id="sortByValue" name="s">
                  <input type="hidden" value="<?= $collection->getId() ?>" name="collection_id">
                </form>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>

        <div class="mycq-collections">
          <div class="row thumbnails">
            <?php include_component('mycq', 'collectibles', array('collection' => $collection)); ?>
          </div>
        </div>
      </div>
      <?php endif; ?>

    </div>
  </div>
</div>

<script>
$(document).ready(function()
{
  $('input.tag').tagedit({
    autocompleteURL: '<?= url_for('@ajax_typeahead?section=tags&page=edit'); ?>',
    // return, comma, semicolon
    breakKeyCodes: [ 13, 44, 59 ]
  });

  $('#collection_description').wysihtml5({
    "font-styles": false, "image": false, "link": false
  });

  $("#form-collection .drop-zone-large").droppable(
  {
    over: function(event, ui)
    {
      $(this).addClass("ui-state-highlight");
      $(this).find('i')
        .removeClass('icon-plus')
        .addClass('icon-download-alt');
      $(this).find('img').hide();
      $(this).find('span.plus-icon-holder').show();
    },
    out: function(event, ui)
    {
      $(this).removeClass("ui-state-highlight");
      $(this).find('i')
        .removeClass('icon-download-alt')
        .addClass('icon-plus');
      $(this).find('span.plus-icon-holder').hide();
      $(this).find('img').show();
    },
    drop: function(event, ui)
    {
      $(this).removeClass("ui-state-highlight");
      $(this).find('i')
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
