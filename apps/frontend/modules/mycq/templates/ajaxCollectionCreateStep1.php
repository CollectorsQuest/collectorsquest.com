<?php
/**
 * @var $form CollectionCreateForm
 */
?>

<form action="<?= url_for('@ajax_mycq?section=collection&page=createStep1'); ?>"
      method="post" id="fileupload" class="ajax form-horizontal form-modal" enctype="multipart/form-data">

  <h1>Create Collection - Step 1</h1>
  <?= $form ?>

  <?php /*
  <div id="dropzone-wrapper" class="dropzone-container">
    <div id="dropzone" class="collectibles-to-sort no-items-to-sort-box Chivo webfont spacer-inner">
      <span class="info-no-items-to-sort" style="text-align: center">
        &nbsp;&nbsp;<strong>Drag and drop</strong> photos from your desktop
      </span>
    </div>
  </div>
  */ ?>

  <?php // include_component('mycq', 'uploadPhotosCollection'); ?>

  <div class="form-actions">
    <button type="submit" class="btn btn-primary spacer-right-15">
      Next
    </button>
    <button type="reset" class="btn"
            onClick="$(this).parents('.modal').find('.modal-body').dialog2('close')">
      Cancel
    </button>
  </div>

</form>
