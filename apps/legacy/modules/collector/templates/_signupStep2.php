<?php
  $rpxnow = sfConfig::get('app_credentials_rpxnow');

  include_partial(
    'global/wizard_bar',
    array('steps' => array(1 => __('Account Information'), __('Collector Information'), __('Personal Information')), 'active' => 2)
  );
?>

<div id="collector_signup_2">

  <?php echo $form->renderGlobalErrors(); ?>
  <?php $ssURL = url_for('@collector_signup?step=2'); ?>

  <form action="<?php echo $ssURL ?>"  method="post" id="form-collector-signup-step2">
    <fieldset class="span-16" style="margin-left: 40px;">
      <legend>Collector Information</legend>

      <div class="span-3" style="text-align: right; width:160px;">
        <?= cq_label_for($form, 'collector_type', __('What type of collector are you?')); ?>
        <div class="required"><?= __('(required)'); ?></div>
      </div>
      <div class="prepend-1 span-6 last">
        <?php echo cq_select_tag($form, 'collector_type'); ?>
        <span style="color:#FF0000"><?= $form['collector_type']->renderError(); ?></span>

        <div id="whatmiContent" style="display:none">
          <p><strong>Occasional:</strong> I buy only once in a blue moon.</p>
          <p><strong>Casual:</strong> If I see something, I might buy it.</p>
          <p><strong>Serious:</strong> I am actively seeking new items all of the time.</p>
          <p><strong>Obsessive:</strong> I need to have everything I can get my hands on.</p>
          <p><strong>Expert:</strong> I work in the trade as a seller/appraiser or have acquired a vast amount of knowledge in the area I collect.</p>
        </div>
      </div>

      <br clear="all"/><br/>

      <div class="span-3" style="text-align: right; width:160px;">
        <?= cq_label_for($form, 'what_you_collect', __('What do you collect?')); ?>
        <div class="required"><?= __('(required)'); ?></div>
      </div>
      <div class="prepend-1 span-6 last"> <?= cq_input_tag($form, 'what_you_collect', array('width' => 400)); ?>
        <span style="color:#FF0000"><?= $form['what_you_collect']->renderError(); ?></span></div>
      <br clear="all"/>
      <br>

      <div class="span-3"
           style="text-align: right; width:160px;"> <?= cq_label_for($form, 'purchase_per_year', __('How many times a year do you purchase?')); ?>
        <div class="required"><?= __('(required)'); ?></div>
      </div>
      <div
          class="prepend-1 span-6 last"> <?= cq_input_tag($form, 'purchase_per_year', array('width' => 400)); ?>
        <span style="color:#FF0000"><?= $form['purchase_per_year']->renderError(); ?></span></div>
      <br clear="all"/>
      <br>

      <div class="span-3"
           style="text-align: right; width:160px;"> <?= cq_label_for($form, 'most_expensive_item', __('What is the most you ever spent on an item? (in USD):')); ?>
        <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
      </div>
      <div
          class="prepend-1 span-6 last"> <?= cq_input_tag($form, 'most_expensive_item', array('width' => 400)); ?>
        <span style="color:#FF0000"><?= $form['most_expensive_item']->renderError(); ?></span></div>
      <br clear="all"/>
      <br>

      <div class="span-3"
           style="text-align: right; width:160px;"> <?= cq_label_for($form, 'annually_spend', __('How much do you spend annually? (in USD):')); ?>
        <div style="color: #ccc; font-style: italic;"><?= __('(optional)'); ?></div>
      </div>
      <div class="prepend-1 span-6 last"> <?= cq_input_tag($form, 'annually_spend', array('width' => 400)); ?>
        <span style="color:#FF0000"><?= $form['annually_spend']->renderError(); ?></span></div>
    </fieldset>
    <div class="span-13" style="text-align: right;">
      <?php cq_button_submit(__('Next'), null, 'padding-left: 350px;'); ?>
    </div>
    <div class="clearfix append-bottom">&nbsp;</div>

    <?= $form['_csrf_token']; ?>
    <input type="hidden" name="first_step_data" value="<?= base64_encode(serialize($amStep1Data)); ?>" readonly="readonly"/>
  </form>
</div>

<div class="clearfix append-bottom">&nbsp;</div>
<div class="prepend-5">
  <a name="openid"></a>
  <iframe src="<?= $rpxnow['application_domain']; ?>/openid/embed?token_url=<?= url_for('@rpx_token', true); ?>"
          scrolling="no" frameBorder="no" style="width:350px; height:215px;" width="350" height="215"></iframe>
</div>

<?php cq_javascript_tag(); ?>
<script type="text/javascript">
  $('#form-collector-signup-step2').submit(function()
  {
    jQuery.ajax(
    {
      url: '<?php echo $ssURL ?>',
      type: 'POST',
      dataType: 'html',
      data: jQuery(this).serialize(),
      success: function(data, textStatus)
      {
        jQuery('#collector_signup_div').html(data);
      },
      beforeSend: function(XMLHttpRequest)
      {
        jQuery('#indicator1').fadeIn('normal' );
      },
      complete: function(XMLHttpRequest, textStatus)
      {
        jQuery('#indicator1').fadeOut('normal' );
      }
    });

    return false;
  });

  function callAjax(ssUpdate, ssURL)
  {
    jQuery.ajax({
      update: ssUpdate,
      type: "POST",
      url: ssURL,
      data:jQuery(this.form.elements).serialize(),
      success: function(data) {
        jQuery("#" + ssUpdate).html(data);
      }
    });

    return false;
  }
</script>
<?php cq_end_javascript_tag(); ?>
