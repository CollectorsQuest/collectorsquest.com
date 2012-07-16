<div class="blue-well spacer-15 cf">
  <div class="row-fluid">
    <div class="span8">
      <div class="buttons-container">
        <a href="<?= url_for('@ajax_mycq?section=component&page=createCollectible&collection_id='. $collection->getId()); ?>"
           class="btn-blue-simple open-dialog">
          <i class="icon icon-plus"></i>
          Add Collectible
        </a>
        <a href="<?= url_for_collection($collection); ?>" class="btn-blue-simple">
          <i class="icon-globe"></i>
          Public View
        </a>
        <a href="#"  class="btn-blue-simple">
          <i class="icon icon-move"></i>
          Re-order Collectibles
        </a>
        <a href="<?= url_for('mycq_collection_by_section', array('id' => $collection, 'section' => 'details', 'cmd' => 'delete', 'encrypt' => '1')); ?>"
           class="btn-delete-simple" onclick="return confirm('Are you sure you want to delete this Collection?');">
          <i class="icon icon-trash"></i>
          Delete Collection
        </a>
      </div>
    </div>
    <div class="span4">
      <div class="mini-input-append-search">
        <div class="input-append pull-right">
          <form method="post" id="form-mycq-collections" action="/ajax/mycq/component/collections">
            <input type="text" name="q" id="appendedPrependedInput" class="input-sort-by"><button type="submit" class="btn gray-button"><strong>Search</strong></button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
