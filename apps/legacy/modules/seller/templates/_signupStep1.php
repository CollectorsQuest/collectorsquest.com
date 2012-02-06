<?php
  include_partial(
    'global/wizard_bar', array('steps' => array(1 => __('Account Information'), __('Personal information'), __('Choose a plan')), 'active' => 1)
  );
?>

<div id="sellersignup_1">

  <?php echo $form->renderGlobalErrors(); ?>
  <?php $ssURL = url_for('@seller_signup?step=1'); ?>

  <form method="post" action="<?php echo $ssURL ?>" onsubmit="jQuery.ajax({type:'POST',dataType:'html',data:jQuery(this).serialize(),success:function(data, textStatus){jQuery('#seller_signup_div').html(data);},beforeSend:function(XMLHttpRequest){jQuery('#indicator1').fadeIn('normal' );},complete:function(XMLHttpRequest, textStatus){jQuery('#indicator1').fadeOut('normal' );},url:'<?php echo $ssURL ?>'}); return false;" name="form-seller-signup-step1">
    <fieldset class="span-16" style="margin-left: 40px;">
      <legend>Account Information</legend>
      <div class="span-3" style="text-align: right;"> <?php echo cq_label_for($form, 'username', __('Username:')); ?>
        <div class="required"><?= __('(required)'); ?></div>
      </div>
      <div class="prepend-1 span-6 last"> <?php echo cq_input_tag($form, 'username', array('width' => 400)); ?> <span style="color:#FF0000"><?php echo $form['username']->renderError(); ?></span> </div>
      <br clear="all"/>
      <br />
      <div class="span-3" style="text-align: right;"> <?php echo cq_label_for($form, 'display_name', __('Display Name:')); ?>
        <div class="required"><?= __('(required)'); ?></div>
      </div>
      <div class="prepend-1 span-6 last"> <?php echo cq_input_tag($form, 'display_name', array('width' => 400)); ?> <span style="color:#FF0000"><?php echo $form['display_name']->renderError(); ?></span> </div>
      <br clear="all"/>
      <br />
      <div class="span-3" style="text-align: right;"> <?php echo cq_label_for($form, 'password', __('Password:')); ?>
        <div class="required"><?= __('(required)'); ?></div>
      </div>
      <div class="prepend-1 span-6 last"> <?php echo cq_input_tag($form, 'password', array('width' => 250)); ?> <span style="color:#FF0000"><?php echo $form['password']->renderError(); ?></span> </div>
      <br clear="all"/>
      <br>
      <div class="span-3" style="text-align: right;"> <?php echo cq_label_for($form, 'password_again', __('Confirm Password:')); ?>
        <div class="required"><?= __('(required)'); ?></div>
      </div>
      <div class="prepend-1 span-6 last"> <?php echo cq_input_tag($form, 'password_again', array('width' => 250)); ?> <span style="color:#FF0000"><?php echo $form['password_again']->renderError(); ?></span> </div>
      <br clear="all"/>
      <br>
      <div class="span-3" style="text-align: right;"> <?php echo cq_label_for($form, 'email', __('E-mail:')); ?>
        <div class="required"><?= __('(required)'); ?></div>
      </div>
      <div class="prepend-1 span-6 last"> <?php echo cq_input_tag($form, 'email', array('width' => 400)); ?> <span style="color:#FF0000"><?php echo $form['email']->renderError(); ?></span> </div>
      <br clear="all"/>
      <br>
      <div class="span-3" style="text-align: right;"> <?php echo cq_label_for($form, 'what_you_sell', __('I Sell:')); ?>
        <div class="required"><?= __('(required)'); ?></div>
      </div>
      <div class="prepend-1 span-6 last"> <?php echo cq_input_tag($form, 'what_you_sell', array('width' => 400)); ?> <span style="color:#FF0000"><?php echo $form['what_you_sell']->renderError(); ?></span> </div>
      <br clear="all"/>
      <br>
      <div class="span-3" style="text-align: right;"> <?php echo cq_label_for($form, 'what_you_collect', __('I Collect:')); ?>
        <div style="color: #ccc; font-style: italic;"><?php echo __('(optional)'); ?></div>
      </div>
      <div class="prepend-1 span-6 last"> <?php echo cq_input_tag($form, 'what_you_collect', array('width' => 400)); ?> <span style="color:#FF0000"><?php echo $form['what_you_collect']->renderError(); ?></span> </div>
      <?php echo $form['_csrf_token']; ?>
    </fieldset>
    <br clear="all"/>
    <div class="span-13" style="text-align: right;">
      <?php cq_button_submit(__('Next'), null, 'padding-left: 350px;'); ?>
    </div>

    <div class="clearfix append-bottom">&nbsp;</div>
    <div class="prepend-5">
      <a name="openid"></a>
      <iframe src="https://collectorsquest.rpxnow.com/openid/embed?token_url=<?php echo url_for('@rpx_token', true); ?>"
              scrolling="no" frameBorder="no" style="width:350px; height:220px;" width="350" height="220"></iframe>
    </div>
  </form>
</div>

<style type="text/css">
  #sellerstep1_password_bar {
    border: 1px solid #E9E9E9;
    font-size: 1px;
    height: 5px;
    width: 0px;
  }

  .pstrength-minchar {
    font-size : 10px;
  }
</style>

<?php cq_javascript_tag(); ?>
<script type="text/javascript">
  function callAjax(ssUpdate, ssURL)
  {
    jQuery.ajax({
      update: ssUpdate,
      type: "POST",
      url: ssURL,
      //	   data:jQuery(this.form.elements).serialize(),
      data: "username="+jQuery("#sellerstep1_username").val()+"&display_name="+jQuery("#sellerstep1_display_name").val()+"&sellerstep1_password="+jQuery("#sellerstep1_password").val()+"&sellerstep1_password_again="+jQuery("#sellerstep1_password_again").val()+"&sellerstep1_email="+jQuery("#sellerstep1_email").val()+"&sellerstep1_what_you_sell="+jQuery("#sellerstep1_what_you_sell").val(),
      success: function(data){
        jQuery("#"+ssUpdate).html(data);
      }
    });
    return false;
  }

  $(document).ready(function()
  {
    $('#sellerstep1_password').pstrength();
  });
</script>
<?php cq_end_javascript_tag(); ?>
