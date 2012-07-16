<?php cq_page_title($collection); ?>

<div id="sortable-collectibles" class="row row-content spacer-top-20">
  <?php foreach ($collectibles as $i => $collectible): ?>
  <div id="collectible-<?=$collectible->getId() ?>" class="span2">
    <?= image_tag_collectible($collectible, '75x75', array('style' => 'cursor: move;')) ?>
  </div>
  <?php endforeach; ?>
</div>

<div class="row text-center">
  <a href="<?= url_for_collection($collection)?>" class="btn btn-primary">
    <i class="icon-ok"></i>
    Finish Reordering
  </a>
</div>

<script type="text/javascript">
$(document).ready(function()
{
  $('#sortable-collectibles').sortable(
  {
    items: 'div',
    containment: '#sortable-collectibles',
    handle: 'img',
    opacity: 0.7,
    revert: true,
    cursor: 'move',

    update: function()
    {
      // Show loading for only Collections with more than 100 Collectibles
      if ($('#sortable-collectibles').find('.span2').length > 100)
      {
        $('#sortable-collectibles').showLoading();
      }

      $.post(
        '<?php echo url_for('@ajax_collection?section=reorder&page=collectibles&id=' . $collection->getId()); ?>',
        {
          items: $('#sortable-collectibles').sortable('serialize'),
          key: 'collectible'
        },
        function() {
          $('#sortable-collectibles').hideLoading();
        }
      );
    }
  });
});
</script>
