<?php
/**
 * @var $collectible Collectible
 *
 * @var $form CollectibleEditForm
 * @var $for_for_sale CollectibleForSaleEditForm
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
                <div class="thumbnail drop-zone-large" data-is-primary="1" style="position: relative;">
                  <?= image_tag_multimedia($image, '300x0'); ?>
                  <i class="icon icon-remove-sign" data-multimedia-id="<?= $image->getId(); ?>"></i>
                  <i class="icon icon-plus hide" style="position: absolute; top: 10%; left: 28%;"></i>
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
            <li class="span4 <?= $has_image ? 'ui-state-full' : 'ui-state-empty'; ?>" style="float: left;">
              <div class="thumbnail drop-zone" data-is-primary="0" style="position: relative;">
                <?php if ($has_image): ?>
                  <?= image_tag_multimedia($multimedia[$i], '150x150', array('width' => 92, 'height' => 92)); ?>
                  <i class="icon icon-remove-sign" data-multimedia-id="<?= $multimedia[$i]->getId(); ?>"></i>
                  <i class="icon icon-plus hide" style="position: absolute; top: 5%; left: 18%;"></i>
                <?php else: ?>
                  <i class="icon icon-plus" style="font-size: 40px; padding-top: 0;"></i>
                  <div style="font-size: 14px; line-height: 18px; font-weight: bold;">
                    Alternate<br/> View
                  </div>
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

      <div class="control-group">
        <?= $form['collection_collectible_list']->renderLabel(); ?>
        <div class="controls"><?= $form['collection_collectible_list']; ?></div>
      </div>
      <div class="control-group">
        <?= $form['name']->renderLabel(); ?>
        <div class="controls"><?= $form['name']; ?></div>
      </div>
      <div class="control-group">
        <?= $form['description']->renderLabel(); ?>
        <div class="controls"><?= $form['description']; ?></div>
      </div>
      <div class="control-group">
        <?= $form['tags']->renderLabel(); ?>
        <div class="controls"><?= $form['tags']; ?></div>
      </div>

      <?php if ($form_for_sale): ?>
      <div class="control-group">
        <?= $form_for_sale['is_ready']->renderLabel('Available for Sale?'); ?>
        <div class="controls switch">
          <label class="cb-enable"><span>Yes</span></label>
          <label class="cb-disable selected"><span>No</span></label>
          <?= $form_for_sale['is_ready']->render(array('class' => 'checkbox hide')); ?>
        </div>
      </div>
      <div id="form-collectible-for-sale" class="hide">
        <div class="control-group">
          <?= $form_for_sale['price']->renderLabel(); ?>
          <div class="controls">
            <?= $form_for_sale['price']->render(array('class' => 'span2 text-center help-inline')); ?>
            <?= $form_for_sale['price_currency']->render(array('class' => 'span2 help-inline')); ?>
          </div>
        </div>
        <div class="control-group">
          <?= $form_for_sale['quantity']->renderLabel(); ?>
          <div class="controls">
            <?= $form_for_sale['quantity']->render(array('class' => 'span2 help-inline text-center')); ?>
          </div>
        </div>
        <div class="control-group">
          <?= $form_for_sale['condition']->renderLabel(); ?>
          <div class="controls">
            <?= $form_for_sale['condition']->render(array('class' => 'span4 help-inline')); ?>
          </div>
        </div>

        <div class="control-group">
          <label class="control-label">Shipping</label>
          <div class="controls">
            <label class="radio">
              <input type="radio" name="optionsRadios" value="option1" checked="">
              Free Shipping
            </label>
            <label class="radio">
              <input class="help-inline" type="radio" name="optionsRadios" value="option1">
              Flat Rate (please specify):
              <input type="text" placeholder="input price" class="span3 help-inline price-indent">
              <select class="span2 help-inline">
                <option value="USD" selected="selected">USD</option>
              </select>
            </label>
          </div>
        </div>
      </div>
      <?php endif; ?>

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
          <button type="submit" class="btn btn-primary blue-button">Save Changes</button>
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

  $('#main-image-set .icon-remove-sign').click(function()
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
  });

  $('#collectible_for_sale_is_ready').change(function()
  {
    $('#form-collectible-for-sale').toggleClass(
      'hide', $(this).attr('checked') !== 'checked'
    );

    if ($(this).attr('checked') !== 'checked')
    {
      $(".cb-disable").click();
    }
    else
    {
      $(".cb-enable").click();
    }
  });

  $(".cb-enable").click(function()
  {
    var parent = $(this).parents('.switch');
    $('.cb-disable',parent).removeClass('selected');
    $(this).addClass('selected');
    $('.checkbox', parent)
      .attr('checked', true)
      .change();
  });

  $(".cb-disable").click(function()
  {
    var parent = $(this).parents('.switch');
    $('.cb-enable',parent).removeClass('selected');
    $(this).addClass('selected');
    $('.checkbox', parent)
      .attr('checked', false)
      .change();
  });

  if ($('#collectible_for_sale_is_ready').attr('checked'))
  {
    $(this).find(".cb-enable").click();
  }
});
</script>
