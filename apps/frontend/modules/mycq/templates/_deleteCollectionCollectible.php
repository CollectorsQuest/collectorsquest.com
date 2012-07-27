<form action="<?= url_for('@ajax_mycq?section=component&page=createCollection'); ?>"
      method="post" id="form-create-collection" class="ajax form-horizontal form-modal">

  <h1>Are you sure?</h1>

  <p>
    Deleting a item also deletes all data associated with it like comments, images, tags, etc.<br/>
    Is that what you want to do? <strong>(can't be undone)</strong>
  </p>

  <br/>
  <label for="confirm-text">TYPE "DELETE" TO CONFIRM DELETION</label>
  <input type="text" name="confirm-text" id="confirm-text" value="" class="av-text" style="width: 97%;">

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
