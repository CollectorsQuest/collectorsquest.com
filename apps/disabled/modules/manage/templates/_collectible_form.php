<?php
/**
 * @var $collectible Collectible
 * @var $form sfFormFieldSchema
 */

ice_use_javascript('jquery/chosen.js');
ice_use_stylesheet('jquery/chosen.css');

?>

<?php echo $form->renderHiddenFields() ?>

<div class="span-4" style="text-align: right;">
  <?php echo cq_label_for($form, 'collection_collectible_list', __('Collection(s):')); ?>
  <div class="optional"><?php echo __('(optional)'); ?></div>
</div>
<div class="prepend-1 span-13 last">
  <?php
    echo $form['collection_collectible_list']->render(array(
      'data-placeholder' => __('Choose one or more of your collections...'),
      'class' => 'chzn-select', 'style' => 'width: 410px'
    ));
  ?>
  <?php echo $form['collection_collectible_list']->renderError(); ?>
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
  <?php echo $form['thumbnail']->renderLabel(); ?>
  <div class="optional"><?php echo __('(optional)'); ?></div>
</div>
<div class="prepend-1 span-13 last">
  <div style="float: right;"><?php echo image_tag_collectible($collectible, '75x75'); ?></div>
  <?php echo $form['thumbnail']->render(); ?>
  <br/><br/>
  <div class="span-10" style="color: grey;">
    All popular image formats are supported but the image file should be less than 5MB in size!
  </div>
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
  <?php echo $form['tags']->renderLabel() ?>
  <div class="required"><?php echo __('(required)'); ?></div>
</div>
<div class="prepend-1 span-13 last">
  <?php echo $form['tags']->renderError() ?>
  <div style="background: #E9E9E9; vertical-align: middle; width: 400px; padding: 5px;">
    <?php echo $form['tags']->render(array('class' => 'tags', 'style' => 'display: none', 'innerClass' => 'selected')) ?>
  </div>
</div>
<div class="clear append-bottom">&nbsp;</div>

<?php if (!empty($form['for_sale'])): ?>

  <?php
    cq_section_title(
      __('Marketplace Information') . '&nbsp; - &nbsp;<small style="color: grey;">' .
      __('If and how you want to sell your collectible') . '</small>',
      'margin-left: 40px;'
    );
  ?>
  <br class="clear">

  <div id="showinmarketplace">
    <div class="span-4" style="text-align: right;">
      <?php echo $form['for_sale']['price']->renderLabel(); ?>
      <div class="required"><?php echo __('(required)'); ?></div>
    </div>
    <div class="prepend-1 span-4 last">
      <?php echo cq_input_tag($form['for_sale'], 'price', array('width' => 100)); ?>
      <?php echo $form['for_sale']['price']->renderError(); ?>
    </div>
    <br><span style="color: grey;">(in United States dollars)</span>
    <div class="clear append-bottom">&nbsp;</div>

    <div class="span-4" style="text-align: right;">
      <?php echo $form['for_sale']['quantity']->renderLabel(); ?>
      <div class="optional"><?php echo __('(optional)'); ?></div>
    </div>
    <div class="prepend-1 span-13 last">
      <?php echo cq_input_tag($form['for_sale'], 'quantity', array('width' => 50)); ?>
      <?php echo $form['for_sale']['quantity']->renderError(); ?>
    </div>
    <div class="clear append-bottom">&nbsp;</div>

    <div class="span-4" style="text-align: right;">
      <?php echo $form['for_sale']['condition']->renderLabel(); ?>
      <div class="optional"><?php echo __('(optional)'); ?></div>
    </div>
    <div class="prepend-1 span-13 last">
      <?php echo cq_select_tag($form['for_sale'], 'condition', array('width' => 200)); ?>
      <?php echo $form['for_sale']['condition']->renderError(); ?>
    </div>
    <div class="clear append-bottom">&nbsp;</div>

    <div class="span-4" style="text-align: right;">&nbsp;
    </div>
    <div class="prepend-1 span-13 last">
      <?php echo $form['for_sale']['is_shipping_free']->render(); ?>
      <label for="<?php echo $form['for_sale']['is_shipping_free']->renderId() ?>"> Free shipping</label>
      <?php echo $form['for_sale']['is_shipping_free']->renderError(); ?>
    </div>
    <div class="span-4" style="text-align: right;">&nbsp;</div>

    <div class="prepend-1 span-13 last">
      <?php echo $form['for_sale']['is_ready']->render(); ?>
      <label for="<?php echo $form['for_sale']['is_ready']->renderId() ?>"><b> Item is ready to be posted to the Marketplace</b></label>
    </div>
    <div class="clear append-bottom">&nbsp;</div>
  </div>
<?php endif; ?>

<?php cq_javascript_tag(); ?>
<script type="text/javascript">
  $(function()
  {
    $(".chzn-select").chosen();
  });
</script>
<?php cq_end_javascript_tag(); ?>
