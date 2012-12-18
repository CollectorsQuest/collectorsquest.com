<?php
/**
 * @var $form SellerPromotionForm
 */
?>

<form action="<?= url_for('ajax_mycq', array('section' => 'promoCode', 'page' => 'Create')); ?>"
      method="post" class="ajax form-horizontal form-modal">

  <h1>Add New Promo Code</h1>
  <?= $form->renderGlobalErrors(); ?>

  <div style="position: relative;">
    <div class="control-group ">
      <?= $form['promotion_name']->renderLabel(); ?>
      <div class="controls">
        <div class="with-required-token">
          <span class="required-token">*</span>
          <?= $form['promotion_name']; ?>
        </div>
      </div>
      <?= $form['promotion_name']->renderError(); ?>
    </div>

    <div class="control-group ">
      <?= $form['promotion_code']->renderLabel(); ?>
      <div class="controls">
        <div class="with-required-token">
          <span class="required-token">*</span>
          <?= $form['promotion_code']->render(array('style' => 'text-transform:uppercase;')); ?>
        </div>
      </div>
      <?= $form['promotion_code']->renderError(); ?>
    </div>


    <div class="control-group ">
      <?= $form['amount_type']->renderLabel(); ?>
      <div class="controls">
        <div class="with-required-token">
          <span class="required-token">*</span>
          <?= $form['amount_type']; ?>
        </div>
      </div>
      <?= $form['amount_type']->renderError(); ?>
    </div>

    <div class="control-group hidden" id="promo-amount">
      <?= $form['amount']->renderLabel(); ?>
      <div class="controls">
        <div class="with-required-token input-prepend">
          <span class="add-on">$</span>
          <span class="required-token" style="font-size: 13px;">*</span>
          <?= $form['amount']->render(array('class' => 'input-small text-right',
          'value' => sprintf('%01.2f', (float) $form['amount']->getValue()))); ?>
        </div>
      </div>
      <?= $form['amount']->renderError(); ?>
    </div>


    <?= $form['collectible_id']->renderRow(); ?>

    <div id="promo-options-optional" class="hidden">
      <div class="row-fluid section-title spacer-top-35">  <div class="span12">
        <h2 class="Chivo webfont">Other options <small style="color: grey;">(optional)</small></h2>
      </div></div>

      <div class="hidden" id="promo-options-collector">
        <?= $form['collector_email']->renderRow(); ?>
      </div>
      <div class="hidden" id="promo-options-expire">
        <?= $form['expire_days']->renderRow(array('class' => 'input-small')); ?>
        <?= $form['quantity']->renderRow(array('class' => 'input-small text-right')); ?>
      </div>

      <?= $form['promotion_desc']->renderRow(); ?>
    </div>

  </div>

  <div class="form-actions">
    <button type="submit" class="btn btn-primary spacer-right-15">
      Save
    </button>
    <button type="reset" class="btn" onClick="$(this).parents('.modal').find('.modal-body').dialog2('close')">
      Cancel
    </button>
  </div>

  <?= $form->renderHiddenFields(); ?>
</form>

<script>
  $(document).ready(function()
  {
    $('#seller_promotion_amount_type').change(function()
    {
      $('#promo-amount').addClass('hidden');
      var val = $(this).val();

      if (val == 'Fixed')
      {
        $('#promo-amount').find('.add-on').html('$');
        $('#promo-amount').removeClass('hidden');
      }
      if (val == 'Percentage')
      {
        $('#promo-amount').find('.add-on').html('%');
        $('#promo-amount').removeClass('hidden');
      }

      if (val == '')
      {
        $('#promo-options-optional').addClass('hidden');
      }
      else
      {
        $('#promo-options-optional').removeClass('hidden');
      }
    }).change();
    $('#seller_promotion_collectible_id').change(function()
    {
      if ($(this).val() == '')
      {
        $('#promo-options-collector').addClass('hidden');
        $('#promo-options-expire').removeClass('hidden');
      }
      else
      {
        $('#promo-options-collector').removeClass('hidden');
        $('#promo-options-expire').addClass('hidden');
      }
    }).change();
  });
</script>
