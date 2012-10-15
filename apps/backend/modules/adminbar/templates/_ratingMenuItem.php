<a href="#rating_modal_<?= $object->getId() ?>" data-toggle="modal">
  <span><?= $label ?></span>
</a>
<div class="modal" id="rating_modal_<?= $object->getId() ?>" style="display: none;" tabindex="-1" role="dialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h3">Rete <?= $label ?> - <?= $object ?></h3>
  </div>
  <div class="modal-body">
    <?php include_component('adminbar', 'ratingObject', array('object'=>$object)) ?>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>
