<?php
/**
 * @var $form CollectionCreateForm
 * @var $collection CollectorCollection
 */
?>

<form action="<?= url_for('@ajax_mycq?section=collection&page=createStep1'); ?>"
      method="post" id="form-create-collection" class="ajax form-horizontal form-modal">

  <h1>Create Collection - Step 1</h1>
  <?= $form ?>

  <?php if (isset($form['content_category_id'])): ?>

    <div class="control-group ">
      <?= $form['content_category_id']->renderLabel('Category') ?>
      <div class="controls">
        <div class="with-required-token">
          <span class="required-token">*</span>
          <?php cq_content_categories_to_ul($categories, array('id' => 'categories', 'tabindex'=>3)); ?>
        </div>
        <p class="help-block">
          Choose a category from the list above which best fits your collection.
        </p>
      </div>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary spacer-right-15">
        Next
      </button>
      <button type="reset" class="btn"
              onClick="$(this).parents('.modal').find('.modal-body').dialog2('close')">
        Cancel
      </button>
    </div>
  <?php else: ?>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary spacer-right-15">
        Next
      </button>
      <button type="reset" class="btn"
              onClick="$(this).parents('.modal').find('.modal-body').dialog2('close')">
        Cancel
      </button>
    </div>
  <?php endif; ?>

</form>

<script>
$(document).ready(function()
{
  var categories_tabindex = $('#categories').attr('tabIndex') || 0;
  $("#categories").attr('tabIndex', 0).columnview({
    multi: false, preview: false,
    onchange: function(element) {
      if (0 < $(element).data('object-id')) {
        $("#collection_content_category_id").val($(element).data('object-id'));
      }
      $('#categories').scrollLeft(500);
      $('.feature', '#categories').hide();
    }
  });

  $('.top', '#categories').attr('role', 'listbox').attr('tabIndex', categories_tabindex);
});
</script>
