<?php
  /* @var $form CollectibleForSaleCreateForm */
  /* @var $collectible Collectible */
  $categories = ContentCategoryQuery::create()
    ->descendantsOfRoot()
    ->findTree();
?>

<style>
  .modal .modal-body .chzn-choices {
    width: 220px;
  }
</style>

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

  <h1>Describe Your Item for Sale - Step 2</h1>
  <?= $form->renderAllErrors(); ?>

  <div style="position: relative;">
    <?php
      if (isset($donor))
      {
        echo image_tag_collectible(
          $donor, '100x100',
          array('style'=> 'position: absolute; top: 0px; right: 10px;')
        );
      }
    ?>

    <?php
      if (isset($form['collectible']['collection_id']))
      {
        echo $form['collectible']['collection_id'];
      }
      else if (isset($form['collectible']['collection_collectible_list']))
      {
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
      </div>
    </div>
  </div>

  <div class="form-actions">
    <button type="submit" class="btn btn-primary spacer-right-15">
      Finish
    </button>
    <button type="reset" class="btn" onClick="$(this).parents('.modal-body.opened').dialog2('close'); return false;">
      Cancel
    </button>
  </div>

  <?= $form->renderHiddenFields() ?>
</form>

<script>
$(document).ready(function()
{
  var $chzn = $(".chzn-select");

  $chzn.find("option:selected").each(function(index, option)
  {
    if ($(option).val() === '') {
      $(option).removeAttr("selected");
    }
  });

  $chzn
    .chosen({ no_results_text: "No collections found for" })
    .change(function() {
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
