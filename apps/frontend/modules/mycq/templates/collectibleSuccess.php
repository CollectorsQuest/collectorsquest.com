<?php
/**
 * @var $collection Collection
 * @var $collectible Collectible
 *
 * @var $form CollectibleEditForm
 * @var $for_for_sale CollectibleForSaleEditForm
 */

slot('mycq_dropbox_info_message', 'Drag a photo into "Alternate View" below');
?>

<form action="<?= url_for('mycq_collectible_by_slug', $collectible); ?>"
      method="post" enctype="multipart/form-data" novalidate
      id="form-collectible" class="form-horizontal">
  <?= $form->renderAllErrors(); ?>

  <?php
    cq_sidebar_title(
      sprintf('%s <small>(%s)</small>', $collectible->getName(), $collection->getName()), null,
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
      <?= $form['name']->renderRow(); ?>

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

    <br clear="all"><br/>
    <div class="row-fluid">
      <div class="span12">
        <div class="form-actions text-center spacer-inner-15">
          <button type="submit" formnovalidate class="btn btn-primary">Save Changes</button>
          <a href="<?= url_for('mycq_collectible_by_slug', $collectible) ?>" class="btn spacer-left">
            Cancel
          </a>
        </div>
      </div>
    </div>
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
      <a href="<?= url_for('mycq_collection_by_section', array('id' => $collection->getId(), 'section' => 'details'))?>"
         class="pull-right">
        See All Items &raquo;
      </a>
    </div>
  </div>

  <ul class="thumbnails">
    <?php foreach ($collectibles as $c): ?>
    <li class="wrapper-90">
      <a href="<?= url_for('mycq_collectible_by_slug', $c); ?>" class="thumb">
        <?= image_tag_collectible($c, '100x100', array('width' => 85, 'height' => 85)); ?>
      </a>
    </li>
    <?php endforeach; ?>

    <!--
    <li class="wrapper-90">
      <div class="drop-zone ui-droppable">
        <i class="icon icon-plus" data-collection-id="3269"></i>
        <span class="drop-zone-txt">Add Item<span>
      </div>
    </li>
    //-->
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
