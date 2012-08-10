<?php
/**
 * @var $form CollectibleForSaleCreateForm
 * @var $collectible Collectible
 */
?>

<?php
  if (isset($collectible) && !$collectible->isNew())
  {
    include_partial(
      'global/loading',
      array('url' => url_for(
          'mycq_collectible_by_slug',
          array('sf_subject' => $collectible, 'available_for_sale' => 'yes')
      ))
    );

    return;
  }
?>

<form action="<?= url_for('@ajax_mycq?section=collectibleForSale&page=create'); ?>"
      method="post" id="form-create-collectible" class="ajax form-horizontal form-modal">

  <h1>Add a New Item for Sale</h1>
  <?= $form->renderGlobalErrors(); ?>

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

  <?php // include_partial('mycq/collectible_form_for_sale', array('form' => $form)); ?>

  <div class="form-actions">
    <button type="submit" class="btn btn-primary spacer-right-15">
      Next Step
    </button>
    <button type="reset" class="btn"
            onClick="$(this).parents('.modal').find('.modal-body').dialog2('close')">
      Cancel
    </button>
  </div>

  <?= $form->renderHiddenFields() ?>
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
});
</script>
