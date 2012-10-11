<?php
/**
 * @var $collectible Collectible
 * @var $sf_user cqFrontendUser
 */
?>

<div class="main-image-set-container">
  <ul class="thumbnails">
    <li class="span12 main-thumb">
      <?php /** @var $image iceModelMultimedia */ ?>
      <?php if ($image = $collectible->getPrimaryImage()): ?>
      <div class="thumbnail drop-zone-large" data-is-primary="1">
        <div class="alt-view-img">
          <div class="hidden greed-rollover">
            <i class="icon icon-plus spacer-top"></i>
            <span class="info-text spacer-top-5">
              Update Photo
            </span>
          </div>
          <?php
            echo image_tag_multimedia(
              $image, '300x0',
              array(
                'width' => 294, 'id' => 'multimedia-'. $image->getId(),
              )
            );
          ?>
          <i class="icon icon-remove-sign" data-multimedia-id="<?= $image->getId(); ?>"></i>
          <i class="icon icon-plus icon-plus-pos hide"></i>
          <?php
            $aviary_hmac_message = $sf_user->hmacSignMessage(
              json_encode(array('multimedia-id' => $image->getId())),
              cqConfig::getCredentials('aviary', 'hmac_secret')
            );
          ?>
          <span class="multimedia-edit holder-icon-edit"
                data-original-image-url="<?= src_tag_multimedia($image, 'original') ?>"
                data-post-data='<?= $aviary_hmac_message; ?>'>
            <i class="icon icon-camera"></i><br/>
            Edit Photo
          </span>
        </div>
      </div>
      <?php else: ?>
      <div class="thumbnail drop-zone-large empty" data-is-primary="1">
        <div class="alt-view-slot">
          <i class="icon icon-plus"></i>
            <span class="info-text">
              Drag and drop your main image here from your <strong>"Uploaded&nbsp;Photos"</strong>
              or use the <strong>Browse</strong> button on the right.
            </span>
        </div>
        <div class="hidden">
          <i class="icon icon-plus spacer-top-15"></i>
            <span class="info-text spacer-top-5">
              Main Photo
            </span>
        </div>
      </div>
      <?php endif; ?>

      <?php if ($collectible->getIsPublic() === false): ?>
        <span class="not-public">NOT PUBLIC</span>
      <?php endif; ?>
    </li>
    <?php for ($i = 0; $i < 3 * (intval(count($multimedia) / 3)  + 1); $i++): ?>
    <?php $has_image = isset($multimedia[$i]) && $multimedia[$i] instanceof iceModelMultimedia; ?>
    <li class="span4 square-thumb <?= $has_image ? 'ui-state-full' : 'ui-state-empty'; ?>">
      <div class="thumbnail drop-zone" data-is-primary="0">
      <?php if ($has_image): ?>
        <div class="alt-view-img">
          <?= image_tag_multimedia($multimedia[$i], '150x150', array('width' => 92, 'height' => 92)); ?>
          <i class="icon icon-remove-sign" data-multimedia-id="<?= $multimedia[$i]->getId(); ?>"></i>
          <i class="icon icon-plus icon-plus-pos hide"></i>
          <?php
            $aviary_hmac_message = $sf_user->hmacSignMessage(
              json_encode(array('multimedia-id' => $multimedia[$i]->getId())),
              cqConfig::getCredentials('aviary', 'hmac_secret')
            );
          ?>
          <span class="multimedia-edit holder-icon-edit"
                data-original-image-url="<?= src_tag_multimedia($multimedia[$i], 'original') ?>"
                data-post-data='<?= $aviary_hmac_message; ?>'>
            <i class="icon icon-camera"></i>
          </span>
        </div>
      <?php else: ?>
        <div class="alt-view-slot">
          <i class="icon icon-plus white-alternate-view"></i>
          <span class="info-text">
            Alternate<br/> View
          </span>
        </div>
      <?php endif; ?>
        <div class="hidden">
          <i class="icon icon-plus white-alternate-view spacer-top"></i>
            <span class="info-text spacer-top-5">
              Add View
            </span>
        </div>
      </div>
    </li>
    <?php endfor; ?>
  </ul>
</div>

<script>
$(document).ready(function()
{
  //  $("#main-image-set").sortable({
  //    items: "li.span4:not(.ui-state-empty)",
  //    containment: 'parent', cursor: 'move',
  //    cursorAt: { left: 50, top: 50 },
  //
  //    update: function(event, ui)
  //    {
  //
  //    }
  //  });

  $("#main-image-set .drop-zone, #main-image-set .drop-zone-large").droppable(
  {
    accept: ".draggable",
    over: function(event, ui)
    {
      var $this = $(this);
      $this.addClass('ui-state-highlight');
      $this.addClass('over');
      $this.find('img').fadeTo('fast', 0)
//      $this.find('i.icon-plus')
//        .removeClass('icon-plus')
//        .addClass('icon-download-alt')
        .show();
    },
    out: function(event, ui)
    {
      var $this = $(this);
      $this.removeClass("ui-state-highlight");
      $this.removeClass('over');
//      $this.find('i.icon-download-alt')
//        .removeClass('icon-download-alt')
//        .addClass('icon-plus');
      $this.find('i.hide').hide();
      $this.find('img').fadeTo('slow', 1);
    },
    drop: function(event, ui)
    {
      var $this = $(this);
      $this.removeClass("ui-state-highlight");
      $this.removeClass('over');
//      $this.find('i.icon-download-alt')
//        .removeClass('icon-download-alt')
//        .addClass('icon-plus');
      ui.draggable.draggable('option', 'revert', false);
      ui.draggable.hide();

      $this.showLoading();

      $.ajax({
        url: '<?= url_for('@ajax_mycq?section=collectible&page=donateImage'); ?>',
        type: 'GET',
        data: {
          recipient_id: '<?= $collectible->getId() ?>',
          donor_id: ui.draggable.data('collectible-id'),
          is_primary: $this.data('is-primary')
        },
        dataType: 'json',
        success: function()
        {
          $('#main-image-set').load(
            '<?= url_for('@ajax_mycq?section=component&page=collectibleMultimedia&collectible_id='. $collectible->getId()); ?>',
            function () { $this.hideLoading(); }
          );
        },
        error: function(data, response)
        {

        }
      });
    }
  });

  $('#main-image-set .icon-remove-sign').click(MISC.modalConfirmDestructive(
    'Delete image', 'Are you sure you want to delete this image?', function()
    {
      var $this = $(this);
      var $icon = $(this);

      $icon.hide();
      $icon.parent('div.alt-view-img').showLoading();

      $.ajax({
        url: '<?= url_for('@ajax_mycq?section=multimedia&page=delete&encrypt=1'); ?>',
        type: 'post', data: { multimedia_id: $icon.data('multimedia-id') },
        success: function()
        {
          $('#main-image-set').load(
            '<?= url_for('@ajax_mycq?section=component&page=collectibleMultimedia&collectible_id='. $collectible->getId()); ?>',
            function () { $this.hideLoading(); }
          );
        },
        error: function()
        {
          $this.hideLoading();
          $icon.show();
        }
      });
    }, true));
});
</script>
