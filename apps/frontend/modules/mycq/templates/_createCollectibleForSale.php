<?php
/**
 * @var $form CollectibleCreateForm
 * @var $collectible Collectible
 */
?>

<form action="<?= url_for('@ajax_mycq?section=component&page=createCollectibleForSale'); ?>"
      method="post" id="form-create-collectible" class="ajax form-horizontal form-modal">

  <h1>Create a New Collectible</h1>

  <?= $form['collectible']['collection_collectible_list']->renderRow() ?>
  <?= $form['collectible']['name']->renderRow() ?>
  <?= $form['collectible']['tags']->renderRow() ?>

  <?php include_partial('mycq/collectible_form_for_sale', array('form' => $form)); ?>

  <div class="form-actions">
    <button type="submit" class="btn btn-primary blue-button spacer-right-15">
      Create Collectible
    </button>
    <button type="reset" class="btn gray-button"
            onClick="$(this).parents('.modal').find('.modal-body').dialog2('close')">
      Cancel
    </button>
  </div>

  <?= $form->renderHiddenFields() ?>
</form>

<?php
  if (isset($collectible) && !$collectible->isNew())
  {
    echo cq_link_to(
      '&nbsp;', 'mycq_collectible_by_slug', $collectible, array('class' => 'auto-close')
    );
  }
?>

<script>
$(document).ready(function()
{
  $(".chzn-select").chosen();

  $('#form-create-collectible input.tag').tagedit({
    autocompleteURL: '<?= url_for('@ajax_typeahead?section=tags&page=edit'); ?>',
    autocompleteOptions: { minLength: 3 },
    // return, comma, semicolon
    breakKeyCodes: [ 13, 44, 59 ]
  });
});
</script>
