<?php
/**
 * @var $form CollectionCreateForm
 * @var $collection CollectorCollection
 */
?>

<h1>Set Description and Upload Thumbanil</h1>

<form action="<?= url_for('ajax_mycq', array('section' => 'collection', 'page' => 'setDetailsAndThumbnail', 'collection-id' => $collection->getId())); ?>"
      method="post" id="blah" class="ajax form-horizontal form-modal">

  <?= $form->renderAllErrors(); ?>
  <?= $form; ?>

  <div class="form-actions">
    <button type="submit" class="btn btn-primary spacer-right-15">
      Save changes
    </button>
    <button type="reset" class="btn"
            onClick="$(this).parents('.modal').find('.modal-body').dialog2('close')">
      Skip step
    </button>
  </div>
</form>

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
});
</script>