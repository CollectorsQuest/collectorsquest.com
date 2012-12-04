<?php
/**
 * @var $collection Collection
 * @var $collectible Collectible
 *
 * @var $form CollectibleEditForm
 * @var $for_for_sale CollectibleForSaleEditForm
 */
?>

<?php
  if ($collectible->getMultimediaCount('image') > 0)
  {
    slot(
      'mycq_dropbox_info_message',
      'To add another view of this item, drag an image
       into the "Alternate View" boxes below your main image.'
    );
  }
  else
  {
    slot(
      'mycq_dropbox_info_message',
      'Drag a photo below to set it as the "Main Image" for this item.'
    );
  }
?>

<form action="<?= url_for('mycq_collectible_by_slug', $collectible); ?>"
      method="post" enctype="multipart/form-data" novalidate
      id="form-collectible" class="form-horizontal">

  <?php
    if ($form->hasErrors())
    {
      echo $form->renderAllErrors();
    }
    else if ($collectible->getIsPublic() === false)
    {
      echo '<div class="alert"><strong>NOTE:</strong>',
           ' Your item will not be discoverable until you fill in all the required information!',
           ' (marked with a <span style="color: #cc0000;">*</span> in the form below)',
           '</div>';
    }
  ?>

  <?php
    cq_section_title(
      sprintf('%s <small>(%s)</small>', $collectible->getName() ?: 'Untitled', $collection->getName()), null,
      array('left' => 10, 'right' => 2, 'class'=>'mycq-red-title row-fluid')
    );
  ?>

  <?php
    include_partial(
      'mycq/partials/collectible_gray_bar',
      array('collection' => $collection, 'collectible' => $collectible)
    );
  ?>

  <div class="row-fluid">

    <div class="span4">
      <div id="main-image-set">
        <?php
          include_component(
            'mycq', 'collectibleMultimedia',
            array('collectible' => $collectible)
          );
        ?>
      </div>
    </div><!-- ./span4 -->

    <div class="span8">
      <?= $form['collection_collectible_list']->renderRow(); ?>
      <?= $form['content_category']->renderRow(); ?>
      <?= $form['name']->renderRow(); ?>

      <?php if (isset($form['thumbnail'])): ?>
        <?php if ($form['is_alt_view']->getWidget() instanceof sfWidgetFormInputCheckbox): ?>
          <div class="control-group">
            <?= $form['thumbnail']->renderLabel('Update Photo'); ?>
            <div class="controls">
              <?= $form['thumbnail']->render(); ?>
              <label><?= $form['is_alt_view']; ?>&nbsp; Add as an alternate view instead?</label>
              <?= $form['thumbnail']->renderError(); ?>
            </div>
          </div>
        <?php else: ?>
          <?= $form['thumbnail']->renderRow(); ?>
        <?php endif;?>
      <?php endif; ?>

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
      ?>
          <div id="collectible_is_for_sale" class="control-group">
            <label class="control-label">Available for Sale?</label>
            <div class="controls switch">
              <label class="cb-enable"><span>Yes</span></label>
              <label class="cb-disable selected"><span>No</span></label>
            </div>
            <br style="clear: both;"/>
          </div>

          <script>
            $(document).ready(function()
            {
              $('#collectible_is_for_sale').click(function()
              {
                if ($('.cb-enable').hasClass('selected')) {
                  $('.cb-enable').removeClass('selected');
                  $('.cb-disable').addClass('selected');
                  $('#want-to-sell-banner').hide();
                } else {
                  $('.cb-enable').addClass('selected');
                  $('.cb-disable').removeClass('selected');
                  $('#want-to-sell-banner').show();
                }
              });
            });
          </script>
      <?php
          cq_ad_slot(
            cq_image_tag('headlines/want-to-sell-this-item.png',
              array(
                'width' => '530', 'height' => '71', 'align' => 'right',
                'alt' => 'Want to sell this item?', 'id' => 'want-to-sell-banner',
                'class' => 'hide'
              )
            ),
            '@seller_packages?return_to='. url_for('mycq_collectible_by_slug', $collectible)
          );
        }
      ?>
    </div><!-- ./span8 -->
  </div> <!-- .row-fluid -->

  <br />

  <div class="form-actions text-center spacer-inner-15">
    <button type="submit" formnovalidate class="btn" name="save" value="Save Changes">
      Save Changes
    </button>
    &nbsp;&nbsp;
    <button type="submit" formnovalidate class="btn btn-primary" name="save_and_go" value="Save & Back to Items">
      Save and Add More Items
    </button>
  </div>

  <?= $form->renderHiddenFields(); ?>
</form>


<?php if (count($collectibles) > 0): ?>
<div class="list-thumbs-10x">

  <div class="row-fluid">
    <div class="span6">
      Edit other items in this collection:
    </div>
    <div class="span6">
      <a href="<?= url_for('mycq_collection_by_section', array('id' => $collection->getId(), 'section' => 'collectibles'))?>"
         class="pull-right">
        See All Items &raquo;
      </a>
    </div>
  </div>

  <ul class="thumbnails">
    <?php foreach ($collectibles as $c): ?>
    <li class="wrapper-90">
      <a href="<?= url_for('mycq_collectible_by_slug', array('sf_subject' => $c, 'return_to' => 'collection')); ?>" class="thumb">
        <?= image_tag_collectible($c, '100x100', array('width' => 85, 'height' => 85)); ?>
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
});
</script>
