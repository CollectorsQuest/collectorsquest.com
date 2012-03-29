<?php
  /** @var $form CollectorSignupStep3Form */

  include_partial(
    'global/wizard_bar',
    array('steps' => array(1 => __('Account Information'), __('Collector Information'), __('Personal Information')), 'active' => 3)
  );
?>

<div id="collector_signup_3">
  <?php echo $form->renderGlobalErrors(); ?>
  <?php $ssURL = url_for('@collector_signup?step=3');?>
  <form action="<?php echo $ssURL ?>"  method="post" id="form-collector-signup-step3">
    <fieldset class="span-16" style="margin-left: 40px;">
      <legend>Personal Information (optional)</legend>
      <div class="span-3" style="text-align: right;"> <?php echo  cq_label_for($form, 'birthday', __('Birthday:')); ?>
        <div style="color: #ccc; font-style: italic;"><?php echo  __('(optional)'); ?></div>
      </div>
      <div class="prepend-1 span-6 last">
        <?php echo  $form['birthday']; ?>
        <span style="color:#FF0000"><?php echo  $form['birthday']->renderError(); ?></span>
      </div>
      <br clear="all"/>
      <br>
      <div class="span-3" style="text-align: right;"> <?php echo  cq_label_for($form, 'gender', __('Gender:')); ?>
        <div style="color: #ccc; font-style: italic;"><?php echo  __('(optional)'); ?></div>
      </div>
      <div class="prepend-1 span-6 last"> <?php echo  cq_select_tag($form, 'gender'); ?> </div>
      <br clear="all"/>
      <br>
      <div class="span-3" style="text-align: right;"> <?php echo  cq_label_for($form, 'zip_postal', __('Zip/Postal Code:')); ?>
        <div style="color: #ccc; font-style: italic;"><?php echo  __('(optional)'); ?></div>
      </div>
      <div class="prepend-1 span-6 last"> <?php echo  cq_input_tag($form, 'zip_postal', array('width' => 100)); ?> </div>
      <br clear="all"/>
      <br>
      <div class="span-3" style="text-align: right;"> <?php echo  cq_label_for($form, 'country', __('Country:')); ?>
        <div class="required"><?php echo  __('(required)'); ?></div>
      </div>
      <div class="prepend-1 span-6 last"> <?php echo  cq_select_tag($form, 'country'); ?>
        <span style="color:#FF0000"><?php echo  $form['country']->renderError(); ?></span>
      </div>
      <br clear="all"/>
      <br>
      <div class="span-3" style="text-align: right;"> <?php echo  cq_label_for($form, 'website', __('Website:')); ?>
        <div style="color: #ccc; font-style: italic;"><?php echo  __('(optional)'); ?></div>
      </div>
      <div class="prepend-1 span-6 last"> <?php echo  cq_input_tag($form, 'website', array('width' => 400)); ?> </div>
      <br clear="all"/><br>
    </fieldset>
    <div class="span-13" style="text-align: right;">
      <?php cq_button_submit(__('Sign up for a Collector Account!'), 'signup-submit', 'padding-left: 350px;'); ?>
    </div>
    <div class="clearfix append-bottom">&nbsp;</div>

    <?php echo  $form['_csrf_token']; ?>
  </form>
</div>
