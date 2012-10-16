<form class="ajax form-horizontal form-modal" method="POST"
      action="<?= url_for(
        '@object_machine_tags?class=' . $class . '&id=' . $id . '&user_id=' . $user_id, true
      ); ?>">
    <?php if ($form->isValid()): ?>
      <div data-alert="alert" class="alert alert-success in">
          <a data-dismiss="alert" class="close">×</a>
          Machine tags saved successfully!
      </div>
    <?php endif ?>

    <?= $form; ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            Save
        </button>
        <button type="reset" class="btn"
                onClick="$(this).parents('.modal').find('.modal-body').dialog2('close')">
            Close
        </button>
    </div>
</form>

<style>
  .modal .modal-body .tagedit-list {
    width: auto;
  }
</style>
