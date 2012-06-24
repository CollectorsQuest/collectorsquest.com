<?php
/**
 * @var $collection CollectorCollection
 * @var $form CollectorCollectionEditForm
 */
?>

<form action="<?= url_for('mycq_collection_by_slug', $collection); ?>" novalidate
      id="form-collection" method="post" enctype="multipart/form-data"
      class="form-horizontal spacer-bottom-reset">

<div class="row-fluid">
  <div class="span3">
    <div class="drop-zone-large thumbnail collection">
      <?php if ($collection->hasThumbnail()): ?>
        <?= image_tag_collection($collection, '190x190'); ?>
        <span class="plus-icon-holder h-center" style="display: none; padding-top: 25px;">
          <i class="icon icon-download-alt icon-white"></i>
        </span>
        <span class="multimedia-edit icon-edit-holder"
          data-original-image-url="<?= src_tag_multimedia($collection->getThumbnail(), 'original') ?>"
          data-post-data='<?= $sf_user->hmacSignMessage(json_encode(array(
              'multimedia-id' => $collection->getThumbnail()->getId(),
          )), cqConfig::getCredentials('aviary', 'hmac_secret')); ?>'
        >
          <i class="icon icon-camera"></i><br/>
          Edit Photo
        </span>
      <?php else: ?>
        <a class="plus-icon-holder h-center" href="#">
          <i class="icon icon-plus icon-white"></i>
        </a>
        <div class="info-text">
          Drag and Drop from <br>"Uploaded Photos"
        </div>
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

    <fieldset style="width: 580px;">
        <?= $form; ?>
    </fieldset>

  </div>
  <div class="row-fluid">
    <div class="span12">
      <div class="form-actions text-center">
        <a href="<?= url_for('mycq_collection_by_slug', array('sf_subject' => $collection, 'cmd' => 'delete', 'encrypt' => '1')); ?>"
           class="btn pull-left spacer-left"
           onclick="return confirm('Are you sure you want to delete this Collection?');">
          <i class="icon icon-trash"></i>
          Delete Collection
        </a>

        <button type="submit" formnovalidate class="btn btn-primary">Save Changes</button>
        <a href="<?= url_for('mycq_collection_by_slug', $collection) ?>"
           class="btn spacer-left">
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

      <div class="tab-content-inner spacer-top-35">
        <div class="row-fluid sidebar-title spacer-inner-bottom">
          <div class="span5 link-align">
            <h3 class="Chivo webfont">Collectibles (<?= $total ?>)</h3>
          </div>
          <div class="span7">
            <?php if ($total > 11): ?>
            <div class="sort-search-box">
              <div class="input-append">
                <form id="form-mycq-collectibles" method="post"
                      action="<?= url_for('@ajax_mycq?section=component&page=collectibles') ?>">
                  <div class="btn-group">
                    <div class="append-left-gray">Sort by <strong id="sortByName"></strong></div>
                    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                      <span class="caret arrow-up"></span><br><span class="caret arrow-down"></span>
                    </a>
                    <ul class="dropdown-menu">
                      <li><a data-sort="position" data-name="" class="sortBy" href="javascript:">Sort by <strong>Position</strong></a></li>
                      <li><a data-sort="most-popular" data-name="Most Popular" class="sortBy" href="javascript:">Sort by <strong>Most Popular</strong></a></li>
                      <li><a data-sort="most-recent" data-name="Most Recent" class="sortBy" href="javascript:">Sort by <strong>Most Recent</strong></a></li>
                    </ul>
                  </div>
                  <input type="text" class="input-sort-by" id="appendedPrependedInput" name="q"><button class="btn" type="submit"><strong>Search</strong></button>
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

    </div>
  </div>
</div>

<script>
$(document).ready(function()
{
  $('input.tag').tagedit({
    autocompleteURL: '<?= url_for('@ajax_typeahead?section=tags&page=edit'); ?>',
    autocompleteOptions: { minLength: 3 },
    // return, comma, semicolon
    breakKeyCodes: [ 13, 44, 59 ]
  });

  $('#collection_description').wysihtml5({
    "font-styles": false, "image": false, "link": false,
    events:
    {
      "load": function() {
        $('#collection_description')
          .removeClass('js-hide')
          .removeClass('js-invisible');
      }
    }
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
