<div class="modal fade hide" data-dynamic="true">
  <div class="modal-body" style="max-height: none; padding: 0;">
    <?= image_tag_multimedia($multimedia, $which); ?>
  <div class="modal-footer">
    <a href="<?= src_tag_multimedia($multimedia, 'original'); ?>" class="btn btn-primary" target="_blank">
      View Fullscreen
    </a>
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>
