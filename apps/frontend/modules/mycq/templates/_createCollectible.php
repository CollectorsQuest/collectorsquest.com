<?php
/**
 * @var $form CollectibleCreateForm
 * @var $collectible Collectible
 */
?>

<?php
  if (isset($collectible) && !$collectible->isNew())
  {
    include_partial(
      'global/loading',
      array('url' => url_for('mycq_collectible_by_slug', $collectible))
    );

    return;
  }
?>

<form action="<?= url_for('@ajax_mycq?section=component&page=createCollectible'); ?>"
      method="post" id="form-create-collectible" class="ajax form-horizontal form-modal">

  <h1>Create a New Collectible</h1>

  <?= $form ?>

  <div class="form-actions">
    <button type="submit" class="btn btn-primary blue-button spacer-right-15">
      Next Step
    </button>
    <button type="reset" class="btn gray-button"
            onClick="$(this).parents('.modal').find('.modal-body').dialog2('close')">
      Cancel
    </button>
  </div>

  <?= $form->renderHiddenFields() ?>
</form>

<script>
  $(document).ready(function()
  {
    $('#form-create-collectible input.tag').tagedit({
      autocompleteURL: '<?= url_for('@ajax_typeahead?section=tags&page=edit'); ?>',
      autocompleteOptions: { minLength: 3 },
      // return, comma, semicolon
      breakKeyCodes: [ 13, 44, 59 ]
    });

    <?php
      if (isset($collectible) && !$collectible->isNew())
      {
        echo '$("#form-create-collectible").showLoading();';
      }
    ?>
  });
</script>
