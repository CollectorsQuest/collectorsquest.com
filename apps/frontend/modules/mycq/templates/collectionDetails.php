<?php
/**
 * @var $collection CollectorCollection
 * @var $form CollectorCollectionEditForm
 */
?>

<?php
  cq_sidebar_title(
    $collection->getName(), null,
    array('left' => 10, 'right' => 2, 'class'=>'mycq-red-title row-fluid')
  );
?>

<?php include_partial('mycq/collection_gray_bar', array('collection' => $collection)); ?>

<div id="mycq-tabs">
  <ul class="nav nav-tabs">
    <li>
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
    <li class="active">
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
      <div class="tab-content-inner">

        <form action="<?= url_for('mycq_collection_by_section', array('id' => $collection->getId(), 'section' => 'details')); ?>" novalidate
              id="form-collection" method="post" enctype="multipart/form-data"
              class="form-horizontal spacer-bottom-reset">
          <?= $form->renderAllErrors(); ?>

          <div class="row-fluid">
            <div class="span3">
              <div class="drop-zone-large thumbnail collection">
                <?php if ($collection->hasThumbnail()): ?>
                <?= image_tag_collection($collection, '190x190'); ?>
                <span class="icon-plus-holder h-center dn spacer-top-25">
                  <i class="icon icon-download-alt icon-white"></i>
                </span>
                <span class="multimedia-edit holder-icon-edit"
                  data-original-image-url="<?= src_tag_multimedia($collection->getThumbnail(), 'original') ?>"
                  data-post-data='<?= $sf_user->hmacSignMessage(json_encode(array(
                    'multimedia-id' => $collection->getThumbnail()->getId(),
                  )), cqConfig::getCredentials('aviary', 'hmac_secret')); ?>'>

                  <i class="icon icon-camera"></i><br/>
                  Edit Photo
                </span>
                <?php else: ?>
                <a class="icon-plus-holder h-center" href="#">
                  <i class="icon icon-plus icon-white"></i>
                </a>
                <div class="info-text">
                  Drag and Drop from <br>"Uploaded Photos"
                </div>
                <?php endif; ?>
              </div>
            </div>

            <div class="span9">
              <fieldset style="width: 580px;">
                <?= $form; ?>
              </fieldset>
            </div>

            <div class="row-fluid">
              <div class="span12">
                <div class="form-actions text-center spacer-inner-15">
                  <button type="submit" formnovalidate class="btn btn-primary">Save Changes</button>
                  <a href="<?= url_for('mycq_collection_by_section', array('id' => $collection->getId(), 'section' => 'collectibles')) ?>"
                     class="btn spacer-left">
                    Cancel
                  </a>
                </div>
              </div>
            </div>
          </div>
        </form>

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
