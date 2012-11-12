<?php
/**
 * @var $total integer
 * @var $batch string
 * @var $instructions array
 *
 * @var $collectibles Collectible[] | PropelObjectCollection
 */
?>
<div id="dropzone-wrapper"
     class="dropzone-container <?= $sf_user->getMycqDropboxOpenState() ? '' : 'hidden' ?>">
  <?php if ($total > 0): ?>
  <div class="row-fluid sidebar-title spacer-bottom-reset">
    <div class="span8">
      <h3 class="Chivo webfont"><?= 'Uploaded Photos ('. $total .')'; ?></h3>
    </div>
    <div class="span4">
    <?php
      if (false && $total > 0)
      {
        echo link_to(
          '<i class="icon-trash"></i> Delete all Photos', '@mycq_dropbox?cmd=empty&encrypt=1',
          array(
            'class' => 'btn btn-mini',
            'onclick' => 'return confirm("Are you sure you want to delete all Uploaded Photos?")'
          )
        );
      }
    ?>
    </div>
  </div>
  <div class="collectibles-to-sort" id="dropzone">
    <ul class="thumbnails">
      <?php foreach ($collectibles as $collectible): ?>
      <li class="span2 thumbnail draggable" data-collectible-id="<?= $collectible->getId(); ?>">
        <?php
          echo image_tag_collectible(
            $collectible, '75x75', array('max_width' => 72, 'max_height' => 72)
          );
        ?>
        <i class="icon icon-remove-sign" data-collectible-id="<?= $collectible->getId(); ?>"></i>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php if (has_slot('mycq_dropbox_info_message')): ?>
    <div class="info-message">
      <?php include_slot('mycq_dropbox_info_message'); ?>
    </div>
    <?php endif; ?>
  </div>
  <?php else: ?>
  <div id="dropzone" class="collectibles-to-sort no-items-to-sort-box Chivo webfont spacer-inner">
    <span class="info-no-items-to-sort">
      &nbsp;&nbsp;<strong>Drag and drop</strong> photos from your computer or
      use the <strong>Upload Photos</strong> button
      <?= cq_image_tag('frontend/arrow-thin-up.png', array('style' => 'margin-top: -35px; margin-left: -5px;')) ?>
    </span>
    <div class="info-drop-here">
      Drop files here
    </div>
  </div>
  <?php endif; ?>
</div>

<script>
  $(document).ready(function()
  {
    $('.collectibles-to-sort li').draggable(
    {
      // containment: '#content',
      appendTo: 'body',
      helper: function() {
        return $(this).clone().removeAttr('id').addClass('ui-draggable-dragging')[0];
      },
      scroll: false,
      handle: 'img',
      opacity: 0.7,
      revert: true,
      cursor: 'move',
      cursorAt: { top: 36, left: 36 },
      zIndex: 2000,
      start: function() {
        $(this).toggleClass('invisible')
      },
      stop: function() {
        $(this).toggleClass('invisible')
      }
    });

    $('.collectibles-to-sort .icon-remove-sign').click(MISC.modalConfirmDestructive(
      'Delete Uploaded Photo', 'Are you sure you want to delete this photo?',
      function()
      {
        var $icon = $(this);

        $(this).hide();
        $icon.parent('li.span2').showLoading();

        $.ajax({
          url: '<?= url_for('@ajax_mycq?section=collectible&page=delete&encrypt=1'); ?>',
          type: 'post', data: { collectible_id: $icon.data('collectible-id') },
          success: function()
          {
            $icon.parent('li.span2').fadeOut('fast', function()
            {
              $(this).hideLoading().remove();

              if ($('.collectibles-to-sort .span2').length === 0)
              {
                window.location.reload();
              }
            });
          },
          error: function()
          {
            $(this).show();
          }
        });
      }, true));

    // Use outerHeight() instead of height() if have padding
    var aboveHeight = $('#slot1').outerHeight() + ($('#admin-bar').length != 0 ? $('#admin-bar').outerHeight()+18 : 0);

    var $div = $('<div></div>')
      .addClass('hide')
      .css('width', $('#dropzone-wrapper').outerWidth())
      .css('height', $('#dropzone-wrapper').outerHeight());

    // when scrolling
    $(window).scroll(function()
    {
      // if scrolled down more than the header’s height
      if (
        $('#dropzone-wrapper').is(':visible') &&
          $('#dropzone-wrapper ul.thumbnails li').length > 0 &&
          $(window).scrollTop() > aboveHeight - 30
        ) {
        $div.removeClass('hide');
        $div.insertBefore($('#dropzone-wrapper'));

        $('#dropzone-wrapper')
          .addClass('fixed')
          .css('top', '0')
          .css('padding-top', '5px');
      }
      else
      {
        $div.addClass('hide');
        $('#dropzone-wrapper')
          .removeClass('fixed')
          .css('padding-top', '15px');
      }
    });
  });
</script>
