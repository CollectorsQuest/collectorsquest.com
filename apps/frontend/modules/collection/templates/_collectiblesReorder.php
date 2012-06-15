<?php cq_page_title('Reorder collectibles in ' . $collection); ?>

<div id="sortable-collectibles" class="row row-content">
  <?php foreach ($collectibles as $i => $collectible): ?>
  <div id="collectible-<?=$collectible->getId() ?>" class="span2">
    <?= image_tag_collectible($collectible, '75x75', array('style' => 'cursor: move;')) ?>
  </div>
  <?php endforeach; ?>
</div>

<div class="row">
  <a href="<?= url_for_collection($collection)?>" class="btn btn-primary blue-button">Finish Reordering</a>
</div>

<script type="text/javascript">
  $(document).ready(function () {
    $('#sortable-collectibles').sortable(
        {
          items:'div',
          handle:'img',
          opacity:0.7,
          revert:true,
          cursor:'move',

          update:function () {
            $.post(
                '<?php echo url_for('@ajax_collection?section=reorder&page=collectibles&id=' . $collection->getId()); ?>',
                {
                  items:$('#sortable-collectibles').sortable('serialize'),
                  key:'collectible'
                }
            );
          }
        });
  });
</script>
