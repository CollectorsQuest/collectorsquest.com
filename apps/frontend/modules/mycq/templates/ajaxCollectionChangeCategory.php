<?php
/**
 * @var $form CollectionCreateForm
 * @var $categories ContentCategory[]
 * @var $collection CollectorCollection
 */
?>

<form action="<?= url_for('@ajax_mycq?section=collection&page=changeCategory&collection_id='.$collection->getId()); ?>"
      method="post" id="form-create-collection" class="ajax form-horizontal form-modal">

  <h1>Change Category</h1>

  <?= $form; ?>
  <?php cq_content_categories_to_ul($categories, array('id' => 'categories', 'tabindex'=>3)); ?>


  <div class="form-actions">
    <button type="submit" class="btn btn-primary spacer-right-15">
      Update Category
    </button>
    <button type="reset" class="btn"
            onClick="$(this).parents('.modal').find('.modal-body').dialog2('close')">
      Cancel
    </button>
  </div>

</form>

<script type="text/javascript">
$(document).ready(function()
{
  $("#categories").columnview({
    multi: false, preview: false,
    onchange: function(element) {
      if (0 < $(element).data('object-id')) {
        $("input#collection_content_category_id").val($(element).data('object-id'));
      }
      $('#categories').scrollLeft(500);
      $('#categories .feature').hide();
    }
  });

  $('#categories top').attr('role', 'listbox').attr('tabIndex', 1);
});
</script>
