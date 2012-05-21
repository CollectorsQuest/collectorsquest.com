<?php
/**
 * @var $form CollectionCreateForm
 * @var $collection CollectorCollection
 */
?>

<form action="<?= url_for('@ajax_mycq?section=component&page=createCollection'); ?>"
      method="post" id="form-create-collection" class="ajax form-horizontal form-modal">

  <h1>Create a new Collection</h1>
  <?= $form ?>
  <div class="form-actions">
    <button type="submit" class="btn btn-primary blue-button spacer-right-15">Next</button>
    <button type="button" class="btn gray-button">Cancel</button>
  </div>
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
    $('#form-create-collection input.tag').tagedit({
      autocompleteURL: '<?= url_for('@ajax_typeahead?section=tags&page=edit'); ?>',
      // return, comma, semicolon
      breakKeyCodes: [ 13, 44, 59 ]
    });
  });
</script>
