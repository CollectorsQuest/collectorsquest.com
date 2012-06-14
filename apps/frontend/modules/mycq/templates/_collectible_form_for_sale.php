<?php
/**
 * @var $form CollectibleForSaleEditForm
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
        <?= $form['price']->render(array('class' => 'span2 text-center help-inline', 'required'=>'required')); ?>
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
  <?php else: ?>
  <div id="not-a-seller-box" class="spacer-bottom-20">
    <div class="inner-yellow-bg">
      <div class="row-fluid">
        <div class="span12">
          <span class="Chivo webfont buy-credits">
            Want to sell this collectible?<br/>
            Start selling now for a small fee.
          </span>
          <div class="row-fluid spacer-inner-top">
            <div class="span6">
              <?= link_to('1 credit / $2.50', '@seller_packages', array('class' => 'btn blue-button')) ?>
              <br/><br/>
              <?= link_to('10 credits / $20', '@seller_packages', array('class' => 'btn blue-button')) ?>
            </div>
            <div class="span6">
              <?= link_to('100 credits / $150', '@seller_packages', array('class' => 'btn blue-button')) ?>
              <br/><br/>
              <?= link_to('Unlimited Credits / $250', '@seller_packages', array('class' => 'btn blue-button')) ?>
            </div>
          </div>
        </div>
      </div>
      <br/>
    </div>
  </div>
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
