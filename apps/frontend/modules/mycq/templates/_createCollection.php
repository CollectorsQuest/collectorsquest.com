<?php
/**
 * @var $form CollectionCreateForm
 * @var $collection CollectorCollection
 */
?>

<form action="<?= url_for('@ajax_mycq?section=component&page=createCollection'); ?>"
      method="post" id="form-create-collection" class="ajax form-horizontal form-modal">

  <h1>Create a New Collection</h1>
  <?= $form ?>

  <?php if (isset($form['content_category_id'])): ?>

    <div class="control-group ">
      <?= $form['content_category_id']->renderLabel('Category') ?>
      <div class="controls">
        <div class="with-required-token">
          <span class="required-token">*</span>
          <?php cq_content_categories_to_ul($categories, 'categories'); ?>
        </div>
      </div>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary blue-button spacer-right-15">
        Create Collection
      </button>
      <button type="reset" class="btn gray-button"
              onClick="$(this).parents('.modal').find('.modal-body').dialog2('close')">
        Cancel
      </button>
    </div>
  <?php else: ?>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary blue-button spacer-right-15">
        Next
      </button>
      <button type="reset" class="btn gray-button">Cancel</button>
    </div>
  <?php endif; ?>

</form>

<?php
  if (isset($collection) && !$collection->isNew())
  {
    echo cq_link_to(
      '&nbsp;', 'mycq_collection_by_slug', $collection, array('class' => 'auto-close')
    );
  }
?>

<script>
$(document).ready(function()
{
  $("#categories").columnview({
    multi: false, preview: false,
    onchange: function(element)
    {
      if (0 < $(element).data('object-id')) {
        $("#collection_content_category_id").val($(element).data('object-id'));
      }
      $('#categories').scrollLeft(500);
      $('#categories .feature').hide();
    }
  });

  $('#form-create-collection input.tag').tagedit({
    autocompleteURL: '<?= url_for('@ajax_typeahead?section=tags&page=edit'); ?>',
    // return, comma, semicolon
    breakKeyCodes: [ 13, 44, 59 ]
  });
});
</script>
