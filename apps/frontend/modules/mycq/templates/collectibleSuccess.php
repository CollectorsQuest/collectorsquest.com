<?php
/**
 * @var $collectible Collectible
 */
?>

<form action="<?= url_for('mycq_collectible_by_slug', $collectible); ?>"
      id="form-collectible" method="post" class="form-horizontal">

  <div class="row-fluid">
    <div class="span4">
      <div id="main-image-set">
        <div class="main-image-set-container">
          <ul class="thumbnails">
            <li class="span12">
              <?php if ($image = $collectible->getPrimaryImage()): ?>
                <?= image_tag_multimedia($image, '300x300'); ?>
              <?php else: ?>
                <div class="thumbnail">
                  <i class="icon icon-download-alt drop-zone-large"></i>
                  <span class="info-text">
                     Drag and drop the main image<br> of your collectible here.
                  </span>
                </div>
              <?php endif; ?>
            </li>
            <?php for ($i = 0; $i < 3; $i++): ?>
            <li class="span4">
              <div class="thumbnail drop-zone">
                <?php if (isset($multimedia[$i]) && $multimedia[$i] instanceof iceModelMultimedia): ?>
                  <?= image_tag_multimedia($multimedia[$i], '150x150', array('width' => 96, 'height' => 96)); ?>
                <?php else: ?>
                  <i class="icon icon-plus"></i>
                <?php endif; ?>
              </div>
            </li>
            <?php endfor; ?>
          </ul>
        </div>
      </div>

    </div><!-- ./span4 -->
    <div class="span8">
      <?php
        $link = link_to(
          'Back to Collection &raquo;',
          'mycq_collection_by_slug', array('sf_subject' => $collection),
          array('class' => 'text-v-middle link-align')
        );
        cq_sidebar_title(
          $collectible->getName(), $link,
          array('left' => 8, 'right' => 4, 'class'=>'spacer-top-reset row-fluid sidebar-title')
        );
      ?>

      <?= $form; ?>
    </div><!-- ./span8 -->

    <div class="row-fluid">
      <div class="span12">

        <div class="form-actions text-center spacer-inner-15">
          <a href="<?= url_for('mycq_collectible_by_slug', array('sf_subject' => $collectible, 'cmd' => 'delete', 'encrypt' => '1')); ?>"
             class="btn red-button spacer-left pull-left spacer-left"
             onclick="return confirm('Are you sure you want to delete this Collectible?');">
            Delete Collectible
          </a>
          <button type="submit" class="btn btn-primary blue-button">Save changes</button>
          <button type="reset" class="btn gray-button spacer-left">Cancel</button>
        </div>

      </div>
    </div>
  </div>
</form>

<div id="mycq-tabs">
  <div class="tab-content">
    <div class="tab-pane active" id="tab1">
    <?php
      include_component(
        'mycq', 'dropbox',
        array('instructions' => array(
          'position' => 'top',
          'text' => 'Drag your alternate views for this Collectible into the drop areas.')
        )
      );
    ?>
    </div>
  </div>
</div>

<?php if (count($collectibles) > 0): ?>
<br/>
<div class="list-thumbs-other-collectibles">
  Other collectibles in the <?= link_to_collection($collection, 'text') ?> collection
  <ul class="thumbnails">
    <?php foreach ($collectibles as $c): ?>
    <li class="span2">
      <a href="<?= url_for('mycq_collectible_by_slug', $c); ?>" class="thumbnail">
        <?= image_tag_collectible($c, '75x75'); ?>
      </a>
    </li>
    <?php endforeach; ?>
  </ul>
</div>
<?php endif; ?>

<script type="text/javascript">
$(document).ready(function()
{
  $(".chzn-select").chosen();

  $('input.tag').tagedit({
    autocompleteURL: '<?= url_for('@ajax_typeahead?section=tags&page=edit'); ?>',
    // return, comma, semicolon
    breakKeyCodes: [ 13, 44, 59 ]
  });

  $('#collectible_description').wysihtml5({
    "font-styles": false, "image": false, "link": false
  });

  $("#main-image-set .drop-zone").droppable(
  {
    over: function(event, ui)
    {
      $(this).addClass("ui-state-highlight");
      $(this).find('img').hide();
      $(this).find('span.plus-icon-holder').show();
    },
    out: function(event, ui)
    {
      $(this).removeClass("ui-state-highlight");
      $(this).find('span.plus-icon-holder').hide();
      $(this).find('img').show();
    },
    drop: function(event, ui)
    {
      $(this).removeClass("ui-state-highlight");
      $(this).find('i')
        .removeClass('icon-download-alt')
        .addClass('icon-plus');
      ui.draggable.draggable('option', 'revert', false);

      $(this).showLoading();

      $.ajax({
        url: '<?= url_for('@ajax_mycq?section=collection&page=setThumbnail'); ?>',
        type: 'GET',
        data: {
          collectible_id: ui.draggable.data('collectible-id'),
          collection_id: '<?= $collection->getId() ?>'
        },
        success: function()
        {
          $('#form-collection').submit();
        },
        error: function()
        {
          // error
        }
      });
    }
  });
});
</script>
