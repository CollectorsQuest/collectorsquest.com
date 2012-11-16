<style>
  .modal .modal-body label.control-label {
    width: auto;
  }
</style>

<form action="<?= url_for('@ajax_mycq?section=account&page=delete'); ?>"
      method="post" id="form-delete-account" class="ajax form-modal">

  <h1>Are you sure?</h1>

  <p>
    Deleting your account also deletes all data associated
    with it like collections, collectibles, items for sale, comments, etc.<br/><br/>
    Is that what you want to do? <strong>(can't be undone)</strong>
  </p>

  <br/>
  <?= $form; ?>

  <div class="form-actions">
    <button type="submit" class="btn btn-danger spacer-right-15">
      Confirm
    </button>
    <button type="reset" class="btn"
            onClick="$(this).parents('.modal').find('.modal-body').dialog2('close')">
      Cancel
    </button>
  </div>

</form>
