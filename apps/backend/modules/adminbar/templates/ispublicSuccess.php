<form class="ajax form-horizontal form-modal" method="POST"
      action="<?= url_for(
        '@object_is_public?class=' . $class . '&id=' . $id, true
      ); ?>">
    <?php if ($form->isValid()): ?>
      <div data-alert="alert" class="alert alert-success in">
          <a data-dismiss="alert" class="close">×</a>
        "<?= $form->getObject() ?>" changed to <?= $form->getObject()->getIsPublic() ? 'Public' : 'Private' ?>
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
  .modal .modal-body form {
    margin-left: 20px;
  }
  .modal .modal-body .radio_list {
    list-style: none;
  }
  .modal .modal-body .radio_list li {
    line-height: 28px;
  }
  .modal .modal-body .radio_list label {
    display: inline;
  }
  .modal .modal-body .radio_list input {
    margin: 0;
  }
</style>