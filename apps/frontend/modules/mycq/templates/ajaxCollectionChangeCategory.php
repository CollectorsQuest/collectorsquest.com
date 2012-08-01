<?php
/**
 * @var $form CollectionCreateForm
 * @var $collection CollectorCollection
 */
?>

<form action="<?= url_for('@ajax_mycq?section=collection&page=changeCategory&collection_id='.$collection->getId()); ?>"
      method="post" id="form-create-collection" class="ajax form-horizontal form-modal">

  <h1>Change categories for <?= $collection; ?></h1>
  <?= $form ?>

  <div class="control-group ">
    <?= $form['content_category_id']->renderLabel('Category') ?>
    <div class="controls">
      <?php cq_content_categories_to_ul($categories, array('id' => 'categories', 'tabindex'=>3)); ?>
    </div>
  </div>

  <div class="form-actions">
    <button type="submit" class="btn btn-primary spacer-right-15">
      Update
    </button>
    <button type="reset" class="btn"
            onClick="$(this).parents('.modal').find('.modal-body').dialog2('close')">
      Cancel
    </button>
  </div>

</form>

<script>
$(document).ready(function()
{
  $("#categories").columnview({
    multi: false, preview: false,
    onchange: function(element) {
      if (0 < $(element).data('object-id')) {
        $("#collection_content_category_id").val($(element).data('object-id'));
      }
      $('#categories').scrollLeft(500);
      $('#categories .feature').hide();
    }
  });

  $('#categories top').attr('role', 'listbox').attr('tabIndex', 1);
});
</script>
