<?php
  /* @var $form CollectibleForSaleCreateForm */
  /* @var $collectible Collectible */
  $categories = ContentCategoryQuery::create()
    ->descendantsOfRoot()
    ->findTree();
?>

<?php
  if (isset($collectible) && !$collectible->isNew())
  {
    include_partial(
      'global/loading',
      array('url' => url_for(
        'mycq_collectible_by_slug',
        array(
          'sf_subject' => $collectible,
          'available_for_sale' => 'yes',
          'return_to' => 'market'
        )
      ))
    );

    return;
  }
?>
<form action="<?= url_for('@ajax_mycq?section=collectibleForSale&page=create'); ?>"
      method="post" id="form-create-collectible" class="ajax form-horizontal form-modal">
<?= $form->renderHiddenFields() ?>

<div class="modal">
  <div class="modal-header">
    <h3>Add a New Item for Sale</h3>
  </div>

  <div class="modal-body">
    <?= $form->renderAllErrors(); ?>

    <?php
      if (isset($form['collectible']['collection_id'])) {
        echo $form['collectible']['collection_id'];
      }
      if (isset($form['collectible']['collection_collectible_list'])) {
        echo $form['collectible']['collection_collectible_list']->renderRow();
      }
    ?>
    <?= $form['collectible']['name']->renderRow() ?>
    <?= $form['collectible']['tags']->renderRow() ?>

    <div class="control-group spacer-bottom-reset">
      <?= $form['collectible']['content_category_id']->renderLabel('Category') ?>
      <div class="controls">
        <div class="with-required-token">
          <span class="required-token">*</span>
          <?php cq_content_categories_to_ul($categories, array('id' => 'categories', 'tabindex'=>3)); ?>
        </div>
        <p class="help-block">
          Choose a category from the list above.
        </p>
      </div>
    </div>
  </div>

  <div class="modal-footer">
    <button type="submit" class="btn btn-primary spacer-right-15">
      Next Step
    </button>
    <button type="reset" class="btn"
            onClick="$(this).parents('.modal-body.opened').dialog2('close'); return false;">
      Cancel
    </button>
  </div>
</div>

</form>

<script>
$(document).ready(function()
{
  $(".chzn-select").find("option:selected").each(function(index, option)
  {
    if ($(option).val() === '') {
      $(option).removeAttr("selected");
    }
  });

  $(".chzn-select")
    .chosen({ no_results_text: "No collections found for" })
    .change(function()
    {
      if ($(this).find("option:selected").val() === '')
      {
        $(this).find("option:selected").removeAttr("selected");

        var name;
        if (name = prompt("Please enter the name of the Collection:"))
        {
          $(this).append($('<option></option>').val(name).html(name).attr('selected', 'selected'));
          $(this).trigger("liszt:updated");
        }
      }
    });

  var categories_tabindex = $('#categories').attr('tabIndex') || 0;
  $("#categories").attr('tabIndex', 0).columnview({
    multi: false, preview: false,
    onchange: function(element) {
      if (0 < $(element).data('object-id')) {
        $("#collectible_for_sale_collectible_content_category_id").val($(element).data('object-id'));
      }
      $('#categories').scrollLeft(500);
      $('.feature', '#categories').hide();
    }
  });

  $('.top', '#categories').attr('role', 'listbox').attr('tabIndex', categories_tabindex);
});
</script>
