<?php
/**
 * @var $collection CollectorCollection
 * @var $image iceModelMultimedia
 * @var $aviary_hmac_message string
 */
?>

<div class="drop-zone-large thumbnail collection">
  <?php if (isset($image) && $image instanceof iceModelMultimedia): ?>
    <div class="alt-view-img">
      <?= image_tag_multimedia($image, '190x190'); ?>
      <i class="icon icon-remove-sign" data-multimedia-id="<?= $image->getId(); ?>"></i>
      <span class="multimedia-edit holder-icon-edit"
            data-original-image-url="<?= src_tag_multimedia($image, 'original') ?>"
            data-post-data='<?= $aviary_hmac_message; ?>'>

        <i class="icon icon-camera"></i><br/>
        Edit Photo
      </span>
    </div>
  <?php else: ?>
    <div class="alt-view-slot">
      <span class="icon-plus-holder h-center">
        <i class="icon icon-plus icon-white"></i>
      </span>
      <div class="info-text">
        Drag and drop from <br>"Uploaded Photos"
      </div>
    </div>
  <?php endif; ?>
  <div class="hidden">
    <span class="icon-plus-holder h-center">
     <i class="icon icon-plus"></i>
    </span>
    <span class="info-text spacer-top-5">
      ADD ITEM
    </span>
  </div>

  <?php if ($collection->getIsPublic() === false): ?>
    <span class="not-public">NOT PUBLIC</span>
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
      $icon.parent('div.alt-view-img').showLoading();

      $.ajax({
        url: '<?= url_for('@ajax_mycq?section=multimedia&page=delete&encrypt=1'); ?>',
        type: 'post', data: { multimedia_id: $icon.data('multimedia-id') },
        success: function()
        {
          $('#main-image').load(
            '<?= url_for('@ajax_mycq?section=component&page=collectionMultimedia&collection_id='. $collection->getId()); ?>',
            function () { $icon.parent('div.alt-view-img').hideLoading(); }
          );
        },
        error: function()
        {
          $icon.parent('div.alt-view-img').hideLoading();
          $icon.show();
        }
      });
    }, true));

  $("#form-collection .thumbnail").droppable(
  {
    over: function(event, ui)
    {
      var $this = $(this);
      $this.addClass('ui-state-highlight');
      $this.addClass('over');
      $this.find('img').hide();
    },
    out: function(event, ui)
    {
      var $this = $(this);
      $this.removeClass('ui-state-highlight');
      $this.removeClass('over');
      $this.find('img').show();
    },
    drop: function(event, ui)
    {
      var $this = $(this);
      $this.removeClass('ui-state-highlight');
      $this.removeClass('over');
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
