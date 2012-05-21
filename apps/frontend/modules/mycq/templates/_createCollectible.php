<?php
/**
 * @var $form CollectibleCreateForm
 * @var $collectible Collectible
 */
?>

<form action="<?= url_for('@ajax_mycq?section=component&page=createCollectible'); ?>"
      method="post" id="form-create-collectible" class="ajax form-horizontal form-modal">

  <h1>Create a new Collectible</h1>
  <?= $form ?>
  <div class="form-actions">
    <button type="submit" class="btn btn-primary blue-button spacer-right-15">Create</button>
    <button type="button" class="btn gray-button">Cancel</button>
  </div>
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
    $('#form-create-collectible input.tag').tagedit({
      autocompleteURL: '<?= url_for('@ajax_typeahead?section=tags&page=edit'); ?>',
      // return, comma, semicolon
      breakKeyCodes: [ 13, 44, 59 ]
    });
  });
</script>
