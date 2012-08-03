<?php
/**
 * @var $collection CollectorCollection
 * @var $form CollectorCollectionEditForm
 */
?>

<?php
  cq_sidebar_title(
    $collection->getName(), null,
    array('left' => 10, 'right' => 2, 'class'=>'spacer-top-reset row-fluid sidebar-title')
  );
?>

<?php include_partial('mycq/collection_blue_bar', array('collection' => $collection)); ?>

<div id="mycq-tabs">
  <ul class="nav nav-tabs">
    <li class="active">
      <?php
        echo link_to(
          'Collectibles ('. $total .')',
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
  $('.add-new-zone').on('mouseenter', function() {
    var $this = $(this);
    $this.find('i.icon-plus')
      .removeClass('icon-plus')
      .addClass('icon-hand-up')
      .show();
  });
  $('.add-new-zone').on('mouseleave', function() {
    var $this = $(this);
    $this.find('i.icon-hand-up')
      .removeClass('icon-hand-up')
      .addClass('icon-plus')
      .show();
  });
});
</script>

<script>
$(document).ready(function()
{
  var $url = '<?= url_for('@ajax_mycq?section=component&page=collectibles', true) ?>';
  var $form = $('#form-mycq-collectibles');

  $form.submit(function()
  {
    $('div.mycq-collectibles .thumbnails').fadeOut();

    $.post($url +'?p=1', $form.serialize(), function(data)
    {
      $('div.mycq-collectibles .thumbnails').html(data).fadeIn();
    },'html');

    return false;
  });
});
</script>
