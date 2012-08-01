<?php
/**
 * @var $form CollectionCreateForm
 * @var $collection CollectorCollection
 */
?>

<h1>Create Collection - Step 2</h1>

<form action="<?= url_for('ajax_mycq', array('section' => 'collection', 'page' => 'createStep2', 'collection-id' => $collection->getId())); ?>"
      method="post" id="blah" class="ajax form-horizontal form-modal">

  <?= $form->renderAllErrors(); ?>
  <?= $form; ?>

  <div class="form-actions">
    <button type="submit" class="btn btn-primary spacer-right-15">
      Create Collection
    </button>
    <a href="javascript:void(0)"
       onClick="window.location = '<?= url_for('mycq_collection_by_section', array(
                'id' => $collection->getId(),
                'section' => 'collectibles',
            )); ?>'"
      class="btn">
      Skip this for now
    </a>
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
          .removeClass('js-invisible')
          .removeAttr('required');
      },
      "focus": function() {
        $(editor.composer.iframe).autoResize();
      }
    }
  });
});
</script>
