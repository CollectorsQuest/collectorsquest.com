<?php
/**
 * @var $collection CollectorCollection
 * @var $form CollectorCollectionEditForm
 */
?>

<?php
  cq_section_title(
    $collection->getName(), null,
    array('left' => 10, 'right' => 2, 'class'=>'spacer-top-reset row-fluid sidebar-title')
  );
?>

<div id="mycq-tabs">
  <ul class="nav nav-tabs">
    <li class="active">
    <?php
      echo link_to(
        'Items in Collection ('. $total .')',
        'mycq_collection_by_section', array(
          'id' => $collection->getId(),
          'section' => 'collectibles'
        )
      );
    ?>
    </li>
    <li>
    <?php
      echo link_to(
        'Collection Details',
        'mycq_collection_by_section', array(
          'id' => $collection->getId(),
          'section' => 'details'
        )
      );
    ?>
    </li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active">
      <div class="tab-content-inner spacer-inner-top-reset">

        <div class="row mycq-collectibles">
          <div class="row-content cf" id="sortable-collectibles">

            <?php foreach ($collectibles as $i => $collectible): ?>
            <div id="collectible-<?=$collectible->getId() ?>" class="span2">
              <?= image_tag_collectible($collectible, '75x75', array('style' => 'cursor: move;')) ?>
            </div>
            <?php endforeach; ?>
          </div>
          <div class="row text-center spacer-bottom-15">
            <a href="<?= url_for('mycq_collection_by_section', array('id' => $collection->getId(), 'section' => 'collectibles')) ?>" class="btn btn-primary">
              <i class="icon-ok"></i>
              Finish Reordering
            </a>
          </div>
        </div>

      </div><!-- .tab-content-inner -->
    </div> <!-- .tab-pane.active -->
  </div><!-- .tab-content -->
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
