<?php
/**
 * @var $collectible Collectible
 *
 * @var $form CollectibleEditForm
 * @var $for_for_sale CollectibleForSaleEditForm
 */
?>

<form action="<?= url_for('mycq_collectible_by_slug', $collectible); ?>"
      enctype="multipart/form-data" novalidate
      id="form-collectible" method="post" class="form-horizontal">

  <div class="row-fluid">
    <div class="span4">
      <div id="main-image-set">
        <div class="main-image-set-container">
          <ul class="thumbnails">
            <li class="span12 main-thumb">
              <?php if ($image = $collectible->getPrimaryImage()): ?>
                <div class="thumbnail drop-zone-large" data-is-primary="1">
                  <?php
                    echo image_tag_multimedia(
                      $image, '300x0',
                      array(
                        'width' => 294, 'id' => 'multimedia-'. $image->getId(),
                      )
                    );
                  ?>
                  <i class="icon icon-remove-sign" data-multimedia-id="<?= $image->getId(); ?>"></i>
                  <i class="icon icon-plus icon-plus-pos hide"></i>
                  <span class="multimedia-edit icon-edit-holder"
                    data-original-image-url="<?= src_tag_multimedia($image, 'original') ?>"
                    data-post-data='<?= $sf_user->hmacSignMessage(json_encode(array(
                        'multimedia-id' => $image->getId(),
                    )), cqConfig::getCredentials('aviary', 'hmac_secret')); ?>'
                  >
                    <i class="icon icon-wrench"></i>
                  </span>
                </div>
              <?php else: ?>
                <div class="thumbnail drop-zone-large empty" data-is-primary="1">
                  <i class="icon icon-plus"></i>
                  <span class="info-text">
                    Drag and drop the main image<br> of your collectible here.
                  </span>
                </div>
              <?php endif; ?>
            </li>
            <?php for ($i = 0; $i < 3 * (intval(count($multimedia) / 3)  + 1); $i++): ?>
            <?php $has_image = isset($multimedia[$i]) && $multimedia[$i] instanceof iceModelMultimedia ?>
            <li class="span4 square-thumb <?= $has_image ? 'ui-state-full' : 'ui-state-empty'; ?>">
              <div class="thumbnail drop-zone" data-is-primary="0">
                <?php if ($has_image): ?>
                  <?= image_tag_multimedia($multimedia[$i], '150x150', array('width' => 92, 'height' => 92)); ?>
                  <i class="icon icon-remove-sign" data-multimedia-id="<?= $multimedia[$i]->getId(); ?>"></i>
                  <i class="icon icon-plus icon-plus-pos hide"></i>
                  <span class="multimedia-edit icon-edit-holder"
                    data-original-image-url="<?= src_tag_multimedia($multimedia[$i], 'original') ?>"
                    data-post-data='<?= $sf_user->hmacSignMessage(json_encode(array(
                        'multimedia-id' => $multimedia[$i]->getId(),
                    )), cqConfig::getCredentials('aviary', 'hmac_secret')); ?>'
                  >
                    <i class="icon icon-wrench"></i>
                  </span>
                <?php else: ?>
                  <i class="icon icon-plus white-alternate-view"></i>
                  <span class="info-text">
                    Alternative<br/> View
                  </span>
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

      <?= $form['collection_collectible_list']->renderRow(); ?>
      <?= $form['name']->renderRow(); ?>

      <?php if ($form['is_alt_view']->getWidget() instanceof sfWidgetFormInputCheckbox): ?>
      <div class="control-group">
        <?= $form['thumbnail']->renderLabel(); ?>
        <div class="controls">
          <?= $form['thumbnail']->render(); ?>
          <label>
            &nbsp; <?= $form['is_alt_view']; ?>
            Add as an alternative view instead?
          </label>
          <?= $form['thumbnail']->renderError(); ?>
        </div>
      </div>
      <?php else: ?>
        <?= $form['thumbnail']->renderRow(); ?>
      <?php endif;?>

      <?= $form['description']->renderRow(); ?>
      <?= $form['tags']->renderRow(); ?>

      <?php
        if ($form_for_sale)
        {
          include_partial(
            'mycq/collectible_form_for_sale',
            array('collectible' => $collectible, 'form' => $form_for_sale)
          );
        }
        else
        {
          echo image_tag('banners/want-to-sell-this-item.png', array('align' => 'right'));
          echo '<br clear="all"/><br/>';
        }
      ?>

    </div><!-- ./span8 -->

    <div class="row-fluid">
      <div class="span12">
        <div class="form-actions text-center spacer-inner-15">
          <a href="<?= url_for('mycq_collectible_by_slug', array('sf_subject' => $collectible, 'cmd' => 'delete', 'encrypt' => '1')); ?>"
             class="btn gray-button spacer-left pull-left spacer-left"
             onclick="return confirm('Are you sure you want to delete this Collectible?');">
            <i class="icon icon-trash"></i>
            Delete Collectible
          </a>
          <button type="submit" formnovalidate class="btn btn-primary blue-button">Save Changes</button>
          <a href="<?= url_for('mycq_collectible_by_slug', $collectible) ?>"
             class="btn gray-button spacer-left">
            Cancel
          </a>
        </div>
      </div>
    </div>

  </div>

  <?= $form->renderHiddenFields(); ?>
</form>

<div id="mycq-tabs">
  <div class="tab-content">
    <div class="tab-pane active" id="tab1">
    <?php
      include_component(
        'mycq', 'dropbox',
        array('instructions' => array(
          'position' => 'top',
          'text' => 'Drag your alternative views for this Collectible into the drop areas.')
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
    autocompleteOptions: { minLength: 3 },
    // return, comma, semicolon
    breakKeyCodes: [ 13, 44, 59 ]
  });

  $('#collectible_description').wysihtml5({
    "font-styles": false, "image": false, "link": false,
    events:
    {
      "load": function() {
        $('#collectible_description')
          .removeClass('js-hide')
          .removeClass('js-invisible');
      }
    }
  });

  $( "#main-image-set" ).sortable({
    items: "li.span4:not(.ui-state-empty)",
    containment: 'parent', cursor: 'move',
    cursorAt: { left: 50, top: 50 },

    update: function(event, ui)
    {

    }
  });

  $("#main-image-set .drop-zone, #main-image-set .drop-zone-large").droppable(
  {
    accept: ".draggable",
    over: function(event, ui)
    {
      $(this).addClass("ui-state-highlight");
      $(this).find('img').fadeTo('fast', 0);
      $(this).find('i.icon-plus')
        .removeClass('icon-plus')
        .addClass('icon-download-alt')
        .show();
    },
    out: function(event, ui)
    {
      $(this).removeClass("ui-state-highlight");
      $(this).find('i.icon-download-alt')
        .removeClass('icon-download-alt')
        .addClass('icon-plus');
      $(this).find('i.hide').hide();

      $(this).find('img').fadeTo('slow', 1);
    },
    drop: function(event, ui)
    {
      $(this).removeClass("ui-state-highlight");
      $(this).find('i.icon-download-alt')
        .removeClass('icon-download-alt')
        .addClass('icon-plus');
      ui.draggable.draggable('option', 'revert', false);
      ui.draggable.hide();

      $(this).showLoading();

      $.ajax({
        url: '<?= url_for('@ajax_mycq?section=collectible&page=donateImage'); ?>',
        type: 'GET',
        data: {
          recipient_id: '<?= $collectible->getId() ?>',
          donor_id: ui.draggable.data('collectible-id'),
          is_primary: $(this).data('is-primary')
        },
        dataType: 'json',
        success: function()
        {
          window.location.reload();
        },
        error: function(data, response)
        {
          ;
        }
      });
    }
  });

  $('#main-image-set .icon-remove-sign').click(MISC.modalConfirmDestructive(
    'Delete image', 'Are you sure you want to delete this image?', function()
  {
    var $icon = $(this);

    $icon.hide();
    $icon.parent('div.ui-droppable').showLoading();

    $.ajax({
      url: '<?= url_for('@ajax_mycq?section=multimedia&page=delete&encrypt=1'); ?>',
      type: 'post', data: { multimedia_id: $icon.data('multimedia-id') },
      success: function()
      {
        window.location.reload();
      },
      error: function()
      {
        $(this).hideLoading();
        $icon.show();
      }
    });
  }, true));
});
</script>
