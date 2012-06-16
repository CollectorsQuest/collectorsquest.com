<?php
/**
 * @var $form CollectibleForSaleEditForm
 * @var $form_shipping ShippingRatesCollection
 */
?>

<div class="control-group">
  <?= $form['is_ready']->renderLabel('Available for Sale?'); ?>
  <div class="controls switch">
    <?php $enabled = 'on' == $form['is_ready']->getValue(); ?>
    <label class="cb-enable" for="<?=$form['is_ready']->renderId()?>"><span>Yes</span></label>
    <label class="cb-disable selected" for="<?= $form['is_ready']->renderId() ?>">
      <span>No</span>
    </label>
    <?= $form['is_ready']->render(array('class' => 'checkbox hide')); ?>
  </div>
  <br style="clear: both;"/>
  <?= $form['is_ready']->renderError(); ?>
</div>

<div id="form-collectible-for-sale" class="hide">
  <?php if ($sf_user->getSeller()->hasPackageCredits()): ?>

    <div class="control-group">
      <?= $form['price']->renderLabel(); ?>
      <div class="controls">
        <div class="with-required-token">
          <span class="required-token">*</span>
          <?php
            echo $form['price']->render(array(
              'class' => 'span2 text-center help-inline', 'required'=>'required'
            ));
          ?>
          <?= $form['price_currency']->render(array('class' => 'span2 help-inline')); ?>
        </div>
      </div>
    </div>
    <div class="control-group">
      <?= $form['condition']->renderLabel(); ?>
      <div class="controls">
        <?= $form['condition']->render(array('class' => 'span4 help-inline')); ?>
      </div>
    </div>

    <?php if (IceGateKeeper::open('collectible_shipping') && $form_shipping): ?>
      <?= $form_shipping->renderHiddenFields(); ?>
      <?= $form_shipping->renderUsing('Bootstrap'); ?>
    <?php endif; ?>

  <?php else: ?>
    <center>
      <?php
        echo link_to(
          image_tag('banners/want-to-sell-this-item.png'),
          '@seller_packages'
        );
      ?>
    </center>
    <br/>
  <?php endif; ?>
</div>

<script type="text/javascript">
$(document).ready(function()
{
  $('#collectible_for_sale_is_ready').change(function()
  {
    var checked = $(this).attr('checked') == 'checked';
    $('#form-collectible-for-sale').toggleClass(
      'hide', !checked
    );
    $('.cb-enable').toggleClass('selected', checked);
    $('.cb-disable').toggleClass('selected', !checked);
  }).change();
});
</script>
