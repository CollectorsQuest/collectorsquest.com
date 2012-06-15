<?php
/**
 * @var $total integer
 * @var $batch string
 * @var $instructions array
 *
 * @var $collectibles Collectible[] | PropelObjectCollection
 */
?>

<div id="dropzone-wrapper" class="dropzone-container">
  <div class="row-fluid sidebar-title">
    <div class="span8">
      <h3 class="Chivo webfont"><?= 'Items to Sort ('. $total .')'; ?></h3>
    </div>
    <div class="span4">
      <!--
      <ul class="h-links-small pull-right">
        <li>
          <a href="#">
            View Demo
          </a>
        </li>
        <li>
          <a href="#">
            Help
          </a>
        </li>
      </ul>
      //-->
      <?php
        echo  link_to(
          '<i class="icon-trash"></i> Delete all Items', '@mycq_dropbox?cmd=empty&encrypt=1',
          array(
            'class' => 'btn btn-mini',
            'onclick' => 'return confirm("Are you sure you want to delete all Items to Sort?")'
          )
        );
      ?>
    </div>
  </div>
  <?php if ($total > 0): ?>
  <div class="collectibles-to-sort" id="dropzone">
    <ul class="thumbnails">
      <?php foreach ($collectibles as $collectible): ?>
      <li class="span2 thumbnail draggable" data-collectible-id="<?= $collectible->getId(); ?>">
        <?php
        echo image_tag_collectible(
          $collectible, '75x75', array('max_width' => 72, 'max_height' => 72)
        );
        ?>
        <i class="icon icon-remove-sign" data-collectible-id="<?= $collectible->getId(); ?>"></i>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php else: ?>
  <div id="dropzone" class="collectibles-to-sort no-collections-uploaded-box Chivo webfont">
    <span class="info-no-collections-uploaded">
      There are currently no Items to Sort.<br/>
      Please use the <strong>"Upload Items"</strong> button on the right to get started!
    </span>
  </div>
  <?php endif; ?>
</div>

<?php slot('mycq_dropbox_instructions'); ?>
<div class="row-fluid instruction-box <?= $instructions['position']; ?>">
  <div class="span3">
    <span class="<?php echo ($instructions['position'] === 'top') ? 'gray-arrow-up' : 'gray-arrow' ; ?> pull-right">&nbsp;</span>
  </div>
  <div class="span6 hint-text">
    <?= $instructions['text'] ?>
  </div>
  <div class="span3">
    <span class="<?php echo ($instructions['position'] === 'top') ? 'gray-arrow-up' : 'gray-arrow' ; ?>">&nbsp;</span>
  </div>
</div><!-- /.instruction-box -->
<?php end_slot(); ?>

<script>
$(document).ready(function()
{
  $('.collectibles-to-sort li').draggable(
  {
    // containment: '#content',
    scroll: false,
    handle: 'img',
    opacity: 0.7,
    revert: true,
    cursor: 'move',
    cursorAt: { top: 36, left: 36 },
    zIndex: 1000
  });

  $('.collectibles-to-sort .icon-remove-sign').click(MISC.modalConfirmDestructive(
    'Remove item to sort', 'Are you sure you want to remove this item for sorting?',
    function()
    {
      var $icon = $(this);

      $(this).hide();
      $icon.parent('li.span2').showLoading();

      $.ajax({
        url: '<?= url_for('@ajax_mycq?section=collectible&page=delete&encrypt=1'); ?>',
        type: 'post', data: { collectible_id: $icon.data('collectible-id') },
        success: function()
        {
          $icon.parent('li.span2').fadeOut('fast', function()
          {
            $(this).hideLoading().remove();

            if ($('.collectibles-to-sort .span2').length === 0)
            {
              window.location.reload();
            }
          });
        },
        error: function()
        {
          $(this).show();
        }
      });
    }, true));
});
</script>
