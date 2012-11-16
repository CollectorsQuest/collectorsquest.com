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
    You are trying to delete the whole collection <strong>"<?= $collection->getName() ?>"</strong>.
    <?php if ($collection->getCountCollectibles()): ?>
      <br/>
      This collection has a total of <strong><?= $collection->getCountCollectibles() ?></strong> collectible(s).
    <?php endif; ?>
    <br/><br/>

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
<input type="hidden" name="cmd" value="delete" />
</form>
