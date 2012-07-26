<div class="gray-well cf">
  <div class="row-fluid">
    <div class="span8">
      <ul class="nav nav-pills spacer-bottom-reset">
        <li>
          <a href="<?= url_for('mycq_collections'); ?>">
            <i class="icon-arrow-left"></i>
            Back to Collections
          </a>
        </li>
        <li>
          <a href="<?= url_for('@ajax_mycq?section=component&page=createCollectible&collection_id='. $collection->getId()); ?>"
             class="open-dialog">
            <i class="icon icon-plus"></i>
            Add Item
          </a>
        </li>
        <li>
          <a href="<?= url_for_collection($collection); ?>">
            <i class="icon-globe"></i>
            Public View
          </a>
        </li>
        <li class="dropdown" id="menu1">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#menu1">
            More Actions
            <b class="caret"></b>
          </a>
          <ul class="dropdown-menu">
            <li>
              <a href="<?= url_for('mycq_collection_by_section', array('id' => $collection->getId(), 'section' => 'reorder')); ?>">
                <i class="icon icon-move"></i>
                Re-order Items
              </a>
            </li>
            <li>
              <a href="<?= url_for('mycq_collection_by_section', array('id' => $collection->getId(), 'section' => 'details', 'cmd' => 'delete', 'encrypt' => '1')); ?>"
                 class="btn-delete-simple" onclick="return confirm('Are you sure you want to delete this Collection?');">
                <i class="icon icon-trash"></i>
                Delete Collection
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
    <div class="span4">
      <div class="mini-input-append-search">
        <div class="input-append pull-right">
          <form id="form-mycq-collectibles" method="post">
            <input type="text" class="input-sort-by" id="appendedPrependedInput" name="q"><button class="btn gray-button" type="submit"><strong>Search</strong></button>
            <!-- keep INPUT and BUTTON elements in same line -->
            <input type="hidden" value="<?= $collection->getId() ?>" name="collection_id">
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function()
{
  var $url = '<?= url_for('@ajax_mycq?section=component&page=collectibles', true) ?>';
  var $form = $('#form-mycq-collectibles');

  $form.submit(function()
  {
    $('#collectibles').showLoading();

    $.post($url +'?p=1', $form.serialize(), function(data)
    {
      $('#collectibles').html(data);
      $('#collectibles').hideLoading();
    },'html');

    return false;
  });
});
</script>
