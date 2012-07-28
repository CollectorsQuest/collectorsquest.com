<?php
/**
 * @var $collection CollectorCollection
 * @var $image iceModelMultimedia
 * @var $aviary_hmac_message string
 */
?>

<div class="drop-zone-large thumbnail collection">
  <?php if (isset($image) && $image instanceof iceModelMultimedia): ?>
    <?= image_tag_multimedia($image, '190x190'); ?>
    <span class="icon-plus-holder h-center dn spacer-top-25">
      <i class="icon icon-download-alt icon-white"></i>
    </span>
    <i class="icon icon-remove-sign" data-multimedia-id="<?= $image->getId(); ?>"></i>
    <span class="multimedia-edit holder-icon-edit"
          data-original-image-url="<?= src_tag_multimedia($image, 'original') ?>"
          data-post-data='<?= $aviary_hmac_message; ?>'>

      <i class="icon icon-camera"></i><br/>
      Edit Photo
    </span>
  <?php else: ?>
    <a class="icon-plus-holder h-center" href="#">
      <i class="icon icon-plus icon-white"></i>
    </a>
    <div class="info-text">
      Drag and drop from <br>"Uploaded Photos"
    </div>
  <?php endif; ?>
</div>

<script>
$(document).ready(function()
{
  $('#form-collection .thumbnail .icon-remove-sign').click(MISC.modalConfirmDestructive(
    'Delete image', 'Are you sure you want to delete this image?', function()
    {
      var $icon = $(this);

      $icon.hide();
      $icon.parent('div.ui-droppable').showLoading();

      $.ajax({
        url: '<?= url_for('@ajax_mycq?section=multimedia&page=delete&encrypt=1'); ?>',
        type: 'post', data: { multimedia_id: $icon.data('multimedia-id') },
        success: function()
        {
          $('#main-image').load(
            '<?= url_for('@ajax_mycq?section=component&page=collectionMultimedia&collection_id='. $collection->getId()); ?>',
            function () { $icon.parent('div.ui-droppable').hideLoading(); }
          );
        },
        error: function()
        {
          $icon.parent('div.ui-droppable').hideLoading();
          $icon.show();
        }
      });
    }, true));

  $("#form-collection .thumbnail").droppable(
  {
    over: function(event, ui)
    {
      $(this).addClass('ui-state-highlight');
      $(this).find('.icon-plus-holder i')
        .removeClass('icon-plus')
        .addClass('icon-download-alt');
      $(this).find('img').hide();
      $(this).find('span.icon-plus-holder').show();
    },
    out: function(event, ui)
    {
      $(this).removeClass('ui-state-highlight');
      $(this).find('.icon-plus-holder i')
        .removeClass('icon-download-alt')
        .addClass('icon-plus');
      $(this).find('span.icon-plus-holder').hide();
      $(this).find('img').show();
    },
    drop: function(event, ui)
    {
      var $this = $(this);

      $this.removeClass('ui-state-highlight');
      $this.find('.holder-icon-edit i')
        .removeClass('icon-download-alt')
        .addClass('icon-plus');
      ui.draggable.draggable('option', 'revert', false);

      $this.showLoading();

      $.ajax({
        url: '<?= url_for('@ajax_mycq?section=collection&page=setThumbnail'); ?>',
        type: 'GET',
        data: {
          collectible_id: ui.draggable.data('collectible-id'),
          collection_id: '<?= $collection->getId() ?>'
        },
        success: function()
        {
          $('#main-image').load(
            '<?= url_for('@ajax_mycq?section=component&page=collectionMultimedia&collection_id='. $collection->getId()); ?>',
            function () { $this.hideLoading(); }
          );
        },
        error: function()
        {
          $this.hideLoading();
        }
      });
    }
  });
});
</script>
