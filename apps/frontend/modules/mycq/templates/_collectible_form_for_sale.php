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
  <div id="not-a-seller-box">
    <div class="row-fluid">
      <div class="span9">
        <div class="inner-yellow-bg">
          <div class="row-fluid">
            <div class="span12">
              <span class="Chivo webfont buy-credits">
                Want to sell this collectible?<br/>
                You can start selling for a small fee.
              </span>
              <div class="row-fluid spacer-inner-top-15">
                <div class="span6">
                  <label class="radio">
                    <input type="radio" value="option1" id="optionsRadios1" name="optionsRadios">
                    <strong>1 credit /</strong> $2.50
                  </label>
                  <label class="radio">
                    <input type="radio" value="option2" id="optionsRadios2" name="optionsRadios">
                    <strong>10 credits /</strong> $20
                  </label>
                </div>
                <div class="span6">
                  <label class="radio">
                    <input type="radio" value="option4" id="optionsRadios4" name="optionsRadios">
                    <strong>100 credits /</strong> $150
                  </label>
                  <label class="radio">
                    <input type="radio" value="option5" id="optionsRadios5" name="optionsRadios">
                    <strong>Unlimited Credits /</strong> $250
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="span3">
        <div class="inner-yellow-bg">
          <a href="#" class="btn-create-collection-middle h-center">
            <i class="icon icon-shopping-cart icon-white"></i>
          </a>
          <a href="#" class="blue-link">
            Click here to buy credits
          </a>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>
