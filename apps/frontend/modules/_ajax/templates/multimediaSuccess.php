<div class="modal hide" data-dynamic="true" tabindex="-1">
  <div class="modal-body" style="max-height: none; padding: 0;">
    <?= image_tag_multimedia($multimedia, $which, array('max_width' => 940, 'max_height' => null)); ?>
  <div class="modal-footer">
    <a href="<?= src_tag_multimedia($multimedia, 'original'); ?>" class="btn btn-primary" target="_blank">
      View Fullscreen
    </a>
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>
