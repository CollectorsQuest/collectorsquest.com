<?php /* @var $form sfFormFieldSchema */ ?>
<?php echo $form->renderHiddenFields() ?>

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
  <div style="float: right"><?php echo image_tag_collectible($collectible, '150x150', array('width' => 150, 'height' => 150)); ?></div>
  <?php echo $form['thumbnail']->render(); ?>
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
    <?php echo $form['tags']->render(array('class' => 'tags', 'innerClass' => 'selected')) ?>
  </div>
</div>
<div class="clear append-bottom">&nbsp;</div>

<?php if ($sf_user->hasCredential('seller')): ?>
  <div id="showinmarketplace">
    <div class="span-4" style="text-align: right;">
      <?php echo $form['for_sale']['price']->renderLabel(); ?>
      <div class="required"><?php echo __('(required)'); ?></div>
    </div>
    <div class="prepend-1 span-13 last">
      <?php echo $form['for_sale']['price']->renderError(); ?>
      <?php echo $form['for_sale']['price']->render(); ?>
    </div>
    <div class="clear append-bottom">&nbsp;</div>

    <div class="span-4" style="text-align: right;">
      <?php echo $form['for_sale']['quantity']->renderLabel(); ?>
      <div class="optional"><?php echo __('(optional)'); ?></div>
    </div>
    <div class="prepend-1 span-13 last">
      <?php echo $form['for_sale']['quantity']->renderError(); ?>
      <?php echo $form['for_sale']['quantity']->render(); ?>
    </div>
    <div class="clear append-bottom">&nbsp;</div>

    <div class="span-4" style="text-align: right;">
      <?php echo $form['for_sale']['condition']->renderLabel(); ?>
      <div class="optional"><?php echo __('(optional)'); ?></div>
    </div>
    <div class="prepend-1 span-13 last">
      <?php echo $form['for_sale']['condition']; ?>
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
