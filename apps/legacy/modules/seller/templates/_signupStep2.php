<?php
  use_helper('Form');

  include_partial(
    'global/wizard_bar',
    array('steps' => array(1 => __('Account Information'), __('Personal information'), __('Choose a plan')), 'active' => 2)
  );
?>

<div id="sellersignup_2">
  <?php echo $form->renderGlobalErrors(); ?>
  <?php $ssURL = url_for('@seller_signup?step=2'); ?>

  <form method="post" action="<?php echo $ssURL ?>" name="form-seller-signup-step2">
    <fieldset class="span-16" style="margin-left: 40px;">
    <legend>Personal Information</legend>
      <div class="span-3" style="text-align: right;"> <?php echo  cq_label_for($form, 'birthday', __('Birthday:')); ?>
        <div style="color: #ccc; font-style: italic;"><?php echo  __('(optional)'); ?></div>
      </div>
      <div class="prepend-1 span-6 last"> <?php echo  $form['birthday']; ?> </div>
      <br clear="all"/>
      <br>
      <div class="span-3" style="text-align: right;"> <?php echo  cq_label_for($form, 'gender', __('Gender:')); ?>
        <div style="color: #ccc; font-style: italic;"><?php echo  __('(optional)'); ?></div>
      </div>
      <div class="prepend-1 span-6 last"> <?php echo  cq_select_tag($form, 'gender'); ?> </div>
      <br clear="all"/>
      <br>
      <div class="span-3" style="text-align: right;"> <?php echo  cq_label_for($form, 'zip_postal', __('Zip/Postal Code:')); ?>
        <div class="required"><?php echo  __('(required if from US)'); ?></div>
      </div>
      <div class="prepend-1 span-6 last">
        <?php echo $form['zip_postal']->renderError() ?>
        <?php echo $form['zip_postal']->render(array('style'=>'width: 100px; border: 1px solid #A7A7A7; font-size: 16px; height: 23px; width: 90px; padding: 4px; margin: 0;')) ?>
        <?php //echo cq_input_tag($form, 'zip_postal', array('width' => 100)); ?>
      </div>
      <br clear="all"/>
      <br>
      <div class="span-3" style="text-align: right;">
        <?php echo  cq_label_for($form, 'country', __('Country:')); ?>
            <div class="required"><?= __('(required)'); ?></div>
      </div>
      <div class="prepend-1 span-6 last">
        <?php echo  cq_select_tag($form, 'country'); ?>
        <span style="color:#FF0000"><?php echo  $form['country']->renderError(); ?></span></div>
      <br clear="all"/>
      <br>
      <div class="span-3" style="text-align: right;"> <?php echo  cq_label_for($form, 'website', __('Website:')); ?>
        <div style="color: #ccc; font-style: italic;"><?php echo  __('(optional)'); ?></div>
      </div>
      <div class="prepend-1 span-6 last"> <?php echo  cq_input_tag($form, 'website', array('width' => 400)); ?> </div>
      <br clear="all"/>
      <br>
      <div class="span-3" style="text-align: right;"> <?php echo  cq_label_for($form, 'company', __('Company:')); ?>
        <div style="color: #ccc; font-style: italic;"><?php echo  __('(optional)'); ?></div>
      </div>
      <div class="prepend-1 span-6 last"> <?php echo  cq_input_tag($form, 'company', array('width' => 400)); ?> </div>
    </fieldset>
    <div class="span-13" style="text-align: right;">
      <?php cq_button_submit(__('Next'), null, 'padding-left: 350px;'); ?>
    </div>
    <div class="clearfix append-bottom">&nbsp;</div>

    <?= input_hidden_tag('previous_data', serialize($amPreviousData), array('readonly' => true)); ?>
    <?= $form['_csrf_token']; ?>
  </form>

  <div class="clearfix append-bottom">&nbsp;</div>
  <div class="prepend-5">
    <a name="openid"></a>
    <iframe src="https://collectorsquest.rpxnow.com/openid/embed?token_url=<?php echo  url_for('@rpx_token', true); ?>"
          scrolling="no" frameBorder="no" style="width:350px; height:220px;" width="350" height="220"></iframe>
  </div>
</div>

<?php cq_javascript_tag(); ?>
<script type="text/javascript">
function callAjax(ssUpdate, ssURL)
{
  jQuery.ajax(
  {
     update: ssUpdate,
     type: "POST",
     url: ssURL,
     data:jQuery(this.form.elements).serialize(),
     success: function(data)
     {
       jQuery("#"+ssUpdate).html(data);
     }
  });

  return false;
}
</script>
<?php cq_end_javascript_tag(); ?>
