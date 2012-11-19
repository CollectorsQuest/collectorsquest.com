<?php
/**
 * @var $collection CollectorCollection
 * @var $form CollectorCollectionEditForm
 */

slot(
  'mycq_dropbox_info_message',
  'To add an item to your collection, drag it into the "Add New Item" box below.'
);
?>

<?php
  cq_section_title(
    format_number_choice(
      '[0] %1% <small>(no items yet)</small>|[1] %1% <small>(1 item)</small>|(1,+Inf] %1% <small>(%2% items)</small>',
      array(
        '%1%' => $collection->getName(),
        '%2%' => number_format($total)),
      $total
    ), null,
    array('left' => 10, 'right' => 2, 'class'=>'mycq-red-title row-fluid')
  );
?>

<?php
  include_partial(
    'mycq/partials/collection_gray_bar',
    array('collection' => $collection)
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
          <div class="row-content" id="collectibles">
            <?php include_component('mycq', 'collectibles', array('collection' => $collection)); ?>
          </div>
        </div>

      </div><!-- .tab-content-inner -->
    </div> <!-- .tab-pane.active -->
  </div><!-- .tab-content -->
</div>

<script>
$(document).ready(function()
{
  var $url = '<?= url_for('@ajax_mycq?section=component&page=collectibles', true) ?>';
  var $form = $('#form-mycq-collectibles');

  $(".mycq-create-collectible").droppable({
    activeClass: 'ui-state-hover'
  });

  $form.submit(function() {
    $('div.mycq-collectibles .thumbnails').fadeOut();

    $.post($url +'?p=1', $form.serialize(), function(data) {
      $('div.mycq-collectibles .thumbnails').html(data).fadeIn();
    },'html');

    return false;
  });
});
</script>
