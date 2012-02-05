<?php
/**
 * @var  Collectible          $collectible
 * @var  CollectibleEditForm  $form
 */
?>
<br class="clear" />

<form action="<?php echo url_for('@manage_collectible?id='. $collectible->getId()); ?>" method="post" enctype="multipart/form-data">
  <div class="span-4" style="text-align: right;">
    Collection:
  </div>
  <div class="prepend-1 span-13 last">
    <?= $collectible->getCollection(); ?> &nbsp;
    <?= link_to_function('(change?)', 'fancybox_collectible_choose_collection()'); ?>
  </div>
  <div class="clear append-bottom">&nbsp;</div>
  <div class="span-4" style="text-align: right;">
    <?php echo cq_label_for($form, 'name', __('Name:')); ?>
    <div class="required"><?php echo __('(required)'); ?></div>
  </div>
  <div class="prepend-1 span-13 last">
    <?php echo cq_input_tag($form, 'name', array('width' => 400)); ?>
    <?php echo $form['name']->renderError(); ?>
  </div>
  <div class="clear append-bottom">&nbsp;</div>

  <div class="span-4" style="text-align: right;">
    <?php echo cq_label_for($form, 'thumbnail', __('Thumbnail Image:')); ?>
    <div class="optional"><?php echo __('(optional)'); ?></div>
  </div>
  <div class="prepend-1 span-13 last">
    <div style="float: right"><?php echo image_tag_collectible($collectible, '150x150', array('width' => 150, 'height' => 150)); ?></div>
    <?php echo $form['thumbnail']; ?>
    <?php echo $form['thumbnail']->renderError(); ?>
  </div>
  <div class="clear append-bottom">&nbsp;</div>

  <div class="span-4" style="text-align: right;">
    <?php echo cq_label_for($form, 'description', __('Description:')); ?>
    <div class="required"><?php echo __('(required)'); ?></div>
  </div>
  <div class="prepend-1 span-13 last">
    <?php echo cq_textarea_tag($form, 'description', array('width' => 500, 'height' => 200, 'rich' => true)); ?>
    <?php echo $form['description']->renderError() ?>
  </div>
  <div class="clear append-bottom">&nbsp;</div>

  <div class="span-4" style="text-align: right;">
    <?php echo cq_label_for($form, 'tags', __('Tags / Keywords:')); ?>
    <div class="required"><?php echo __('(required)'); ?></div>
  </div>
  <div class="prepend-1 span-13 last">
    <?php $tags = isset($defaults['tags']) ? $defaults['tags'] : $collectible->getTags(); ?>
    <div style="background: #E9E9E9; vertical-align: middle; width: 400px; padding: 5px;">
      <select id="collectible_tags" name="collectible[tags][]">
        <?php foreach ($tags as $tag): ?>
          <option value="<?php echo $tag; ?>" class="selected"><?php echo $tag; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <?php echo $form['tags']->renderError() ?>
  </div>
  <div class="clear append-bottom">&nbsp;</div>
  <?php if ($bIsSeller): ?>
    <div id="showinmarketplace">
      <?php echo $omItemForSaleForm->renderHiddenFields() ?>
      <div class="span-4" style="text-align: right;">
        <?php echo cq_label_for($omItemForSaleForm, 'price', __('Price:')); ?>
        <div class="required"><?php echo __('(required)'); ?></div>
      </div>
      <div class="prepend-1 span-13 last">
        <?php echo $omItemForSaleForm['price']->renderError(); ?>
        <?php echo $omItemForSaleForm['price']->render(); ?>
      </div>
      <div class="clear append-bottom">&nbsp;</div>

      <div class="span-4" style="text-align: right;">
        <?php echo cq_label_for($omItemForSaleForm, 'quantity', __('Quantity:')); ?>
        <div class="optional"><?php echo __('(optional)'); ?></div>
      </div>
      <div class="prepend-1 span-13 last">
        <?php echo $omItemForSaleForm['quantity']->renderError(); ?>
        <?php echo $omItemForSaleForm['quantity']->render(); ?>
      </div>
      <div class="clear append-bottom">&nbsp;</div>

      <div class="span-4" style="text-align: right;">
        <?php echo cq_label_for($omItemForSaleForm, 'condition', __('Condition:')); ?>
        <div class="optional"><?php echo __('(optional)'); ?></div>
      </div>
      <div class="prepend-1 span-13 last">
        <?php echo $omItemForSaleForm['condition']; ?>
        <?php echo $omItemForSaleForm['condition']->renderError(); ?>
      </div>
      <div class="clear append-bottom">&nbsp;</div>

      <div class="span-4" style="text-align: right;">&nbsp;
      </div>
      <div class="prepend-1 span-13 last">
        <?php echo $omItemForSaleForm['is_shipping_free']; ?><label for="<?php echo $omItemForSaleForm['is_shipping_free']->renderId() ?>"> Free shipping</label>
        <?php echo $omItemForSaleForm['is_shipping_free']->renderError(); ?>
      </div>
      <div class="span-4" style="text-align: right;">&nbsp;
      </div>
      <div class="prepend-1 span-13 last">
        <?php echo $omItemForSaleForm['is_ready']->render(); ?>
        <label for="<?php echo $omItemForSaleForm['is_ready']->renderId() ?>"><b> Item is ready to be posted to the Marketplace</b></label>
      </div>
      <div class="clear append-bottom">&nbsp;</div>
    </div>
    <?php echo input_hidden_tag('collection_item_for_sale[collection_id]', $collectible->getCollectionId()); ?>
  <?php endif; ?>
  <div class="clear append-bottom">&nbsp;</div>
  <div class="span-18" style="text-align: right;">
    <?php cq_button_submit(__('Save Changes'), null, 'float: right;'); ?>
  </div>

  <?php echo $form['_csrf_token']; ?>
</form>

<script src="/js/jquery/tags.js" type="text/javascript"></script>

<?php cq_javascript_tag(); ?>
<script type="text/javascript">
  /*
$(document).ready(function(){

  //Hide div w/id extra
   $("#showinmarketplace").css("display","none");

  // Add onclick handler to checkbox w/id checkme
   $("#collection_item_for_sale_is_post_at_marketplace").click(function(){
  // If checked
  if ($("#collection_item_for_sale_is_post_at_marketplace").is(":checked")){
    //show the hidden div
    $("#showinmarketplace").show("fast");
  }
  else{//otherwise, hide it
    $("#showinmarketplace").hide("fast");
  }
  });
  // While Error Occured and Checkbox is checked for posted on marketplace then so the div.
  if ($("#collection_item_for_sale_is_post_at_marketplace").is(":checked")){
    //show the hidden div
    $("#showinmarketplace").show("fast");
  }
});
   */
  $(function()
  {
    $('#collectible_description').tinymce(
    {
      script_url: '/js/tiny_mce/tiny_mce.js',
      content_css : "/css/legacy/tinymce.css",

      theme: "advanced",
      theme_advanced_buttons1: "formatselect,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo",
      theme_advanced_buttons2: "",
      theme_advanced_buttons3: "",
      theme_advanced_toolbar_location: "external",
      theme_advanced_toolbar_align: "left",
      theme_advanced_resizing: true
    });

    $('#collectible_tags').fcbkcomplete(
    {
      json_url: '<?php echo url_for('@ajax_autocomplete?section=tags'); ?>',
      maxshownitems: 10,
      cache: true,
      filter_case: true,
      filter_hide: true,
      firstselected: true,
      filter_selected: true,
      addoncomma: true,
      input_min_size: 2,
      width: '388px',
      newel: true
    });
  });
</script>
<?php cq_end_javascript_tag(); ?>
