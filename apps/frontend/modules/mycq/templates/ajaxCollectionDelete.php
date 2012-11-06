<?php
/* @var $collection Collection */
?>
<style>
  .modal .modal-body label.control-label {
    width: auto;
  }
</style>

<form action="<?= url_for('mycq_collection_by_section',
  array('id' => $collection->getId(), 'section' => 'details','encrypt' => '1')); ?>"
      method="post" id="form-delete-collection" class="ajax form-modal">

  <h1>Are you sure?</h1>

  <p>

    Deleting collection "<?= $collection->getName() ?>" also delete associated collectibles.<br/><br/>
    <?php if ($collection->getCountCollectibles()): ?>
      Will be deleted <strong><?= $collection->getCountCollectibles() ?></strong> collectible(s) as well.
    <?php endif; ?>
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
<input type="hidden" name="cmd" value="delete" />
</form>
