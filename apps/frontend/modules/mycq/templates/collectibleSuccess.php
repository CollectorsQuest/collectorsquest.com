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
  <?= $form->renderAllErrors(); ?>

  <?php
    cq_sidebar_title(
      $collectible->getName(), null,
      array('left' => 10, 'right' => 2, 'class'=>'spacer-top-reset row-fluid sidebar-title')
    );
  ?>
  <div class="blue-well spacer-bottom-15 cf">
    <div class="pull-left">
      <?php
        if ($collectible->isForSale())
        {
          $link = link_to(
            '← Go to Market', '@mycq_marketplace',
            array('class' => 'btn-blue-simple')
          );
        }
        else
        {
          $link = link_to(
            '← Back to Collection', 'mycq_collection_by_section',
            array('id' => $collection->getId(), 'section' => 'collectibles'),
            array('class' => 'btn-blue-simple')
          );
        }

        echo $link

      ?>
      <a href="<?= url_for_collectible($collectible) ?>"  class="btn-blue-simple">
        <i class="icon-globe"></i>
        Public view
      </a>
      <a href="<?= url_for('mycq_collectible_by_slug', array('sf_subject' => $collectible, 'cmd' => 'delete', 'encrypt' => '1')); ?>"
         class="btn-delete-simple" onclick="return confirm('Are you sure you want to delete this Collectible?');">
        <i class="icon-trash"></i>
        Delete Collectible
      </a>
    </div>
    <?php /*
    <div class="pull-right">
      <a href="#">Back to Collection »</a>
    </div>
    */ ?>
  </div>

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
                  <span class="multimedia-edit holder-icon-edit"
                        data-original-image-url="<?= src_tag_multimedia($image, 'original') ?>"
                        data-post-data='<?= $sf_user->hmacSignMessage(json_encode(array(
                          'multimedia-id' => $image->getId(),
                        )), cqConfig::getCredentials('aviary', 'hmac_secret')); ?>'
                    >
                    <i class="icon icon-camera"></i><br/>
                    Edit Photo
                  </span>
              </div>
              <?php else: ?>
              <div class="thumbnail drop-zone-large empty" data-is-primary="1">
                <i class="icon icon-plus"></i>
                  <span class="info-text">
                    Drag and drop your main image here from your <strong>"Uploaded&nbsp;Photos"</strong>
                    or use the <strong>Browse</strong> button on the right.
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
                <span class="multimedia-edit holder-icon-edit"
                      data-original-image-url="<?= src_tag_multimedia($multimedia[$i], 'original') ?>"
                      data-post-data='<?= $sf_user->hmacSignMessage(json_encode(array(
                        'multimedia-id' => $multimedia[$i]->getId(),
                      )), cqConfig::getCredentials('aviary', 'hmac_secret')); ?>'
                  >
                    <i class="icon icon-camera"></i>
                  </span>
                <?php else: ?>
                <i class="icon icon-plus white-alternate-view"></i>
                <span class="info-text">
                    Alternate<br/> View
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

      <?= $form['collection_collectible_list']->renderRow(); ?>
      <?= $form['name']->renderRow(); ?>

      <?php if ($form['is_alt_view']->getWidget() instanceof sfWidgetFormInputCheckbox): ?>
      <div class="control-group">
        <?= $form['thumbnail']->renderLabel(); ?>
        <div class="controls">
          <?= $form['thumbnail']->render(); ?>
          <label><?= $form['is_alt_view']; ?>&nbsp; Add as an alternate view instead?</label>
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
          'mycq/collectible_form_for_sale', array(
          'collectible' => $collectible,
          'form' => $form_for_sale,
          'form_shipping_us' => $form_shipping_us,
          'form_shipping_zz' => $form_shipping_zz,
        ));
      }
      else
      {
        echo link_to(
          image_tag('banners/want-to-sell-this-item.png', array('align' => 'right')),
          '@seller_packages'
        );
        echo '<br clear="all"/>';
      }
      ?>

    </div><!-- ./span8 -->

    <div class="row-fluid">
      <div class="span12">
        <div class="form-actions text-center spacer-inner-15">
          </a>
          <button type="submit" formnovalidate class="btn btn-primary">Save Changes</button>
          <a href="<?= url_for('mycq_collectible_by_slug', $collectible) ?>"
             class="btn spacer-left">
            Cancel
          </a>
        </div>
      </div>
    </div>

  </div>

  <?= $form->renderHiddenFields(); ?>
</form>


<div class="list-thumbs-10x">

  <div class="row-fluid">
    <div class="span6">
      Edit other collectibles in this collection
    </div>
    <div class="span6">
      <a href="#" class="pull-right">
        Edit collection &raquo;
      </a>
    </div>
  </div>

  <ul class="thumbnails">
    <li class="wrapper-90">
      <a href="#" class="thumb">
        <img alt="" src="http://placehold.it/85x85">
      </a>
    </li>
    <li class="wrapper-90">
      <a href="#" class="thumb">
        <img alt="" src="http://placehold.it/85x85">
      </a>
    </li>
    <li class="wrapper-90">
      <a href="#" class="thumb">
        <img alt="" src="http://placehold.it/85x85">
      </a>
    </li>
    <li class="wrapper-90">
      <a href="#" class="thumb">
        <img alt="" src="http://placehold.it/85x85">
      </a>
    </li>
    <li class="wrapper-90">
      <a href="#" class="thumb">
        <img alt="" src="http://placehold.it/85x85">
      </a>
    </li>
    <li class="wrapper-90">
      <a href="#" class="thumb">
        <img alt="" src="http://placehold.it/85x85">
      </a>
    </li>
    <li class="wrapper-90">
      <a href="#" class="thumb">
        <img alt="" src="http://placehold.it/85x85">
      </a>
    </li>
    <li class="wrapper-90">
      <a href="#" class="thumb">
        <img alt="" src="http://placehold.it/85x85">
      </a>
    </li>
    <li class="wrapper-90">
      <a href="#" class="thumb">
        <img alt="" src="http://placehold.it/85x85">
      </a>
    </li>
    <li class="wrapper-90">
      <div class="drop-zone ui-droppable">
        <i class="icon icon-plus"
           data-collection-id="3269">
        </i>
        <span class="drop-zone-txt">Add item<span>
      </div>
    </li>
    <li class="wrapper-90">
      <a href="#" class="thumb">
        <img alt="" src="http://placehold.it/85x85">
      </a>
    </li>
  </ul>
</div>


<?php if (count($collectibles) > 0): ?>
<br/>
<div class="list-thumbs-other-collectibles">
  Other items in the <?= link_to_collection($collection, 'text') ?> collection
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

    $('#collectible_description').wysihtml5({
      "font-styles": false, "image": false, "link": false,
      events:
      {
        "load": function() {
          $('#collectible_description')
            .removeClass('js-hide')
            .removeClass('js-invisible');
        },
        "focus": function() {
          $(editor.composer.iframe).autoResize();
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
          var $this = $(this);
          $this.addClass('ui-state-highlight');
          $this.find('img').fadeTo('fast', 0);
          $this.find('.holder-icon-edit').hide();
          $this.find('i.icon-plus')
            .removeClass('icon-plus')
            .addClass('icon-download-alt')
            .show();
        },
        out: function(event, ui)
        {
          var $this = $(this);
          $this.removeClass("ui-state-highlight");
          $this.find('i.icon-download-alt')
            .removeClass('icon-download-alt')
            .addClass('icon-plus');
          $this.find('i.hide').hide();
          $this.find('.holder-icon-edit').show();
          $this.find('img').fadeTo('slow', 1);
        },
        drop: function(event, ui)
        {
          var $this = $(this);
          $this.removeClass("ui-state-highlight");
          $this.find('.holder-icon-edit').show();
          $this.find('i.icon-download-alt')
            .removeClass('icon-download-alt')
            .addClass('icon-plus');
          ui.draggable.draggable('option', 'revert', false);
          ui.draggable.hide();

          $this.showLoading();

          $.ajax({
            url: '<?= url_for('@ajax_mycq?section=collectible&page=donateImage'); ?>',
            type: 'GET',
            data: {
              recipient_id: '<?= $collectible->getId() ?>',
              donor_id: ui.draggable.data('collectible-id'),
              is_primary: $this.data('is-primary')
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
