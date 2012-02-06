<style type="text/css">
.ui-state-active, .ui-widget-content .ui-state-active {
  background: #fff;
}
.ui-tabs .ui-tabs-panel {
  padding: 0;
  padding-top: 30px;
}

#collector_password_bar {
  border: 1px solid #E9E9E9;
  font-size: 1px;
  height: 5px;
  width: 0px;
}

.pstrength-minchar {
  font-size : 10px;
}
</style>

<form id="form-manage-profile" action="<?= url_for('@manage_profile'); ?>" method="post" enctype="multipart/form-data">
<div id="profile-tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all" style="margin-top: -5px; margin-right: -5px; background: none; border: none;">
  <ul class="ui-tabs ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" style="margin-bottom: 5px;">
     <li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#profile-tab-account"><?= __('Account Information'); ?></a></li>
     <li class="ui-state-default ui-corner-top ui-state-active"><a href="#profile-tab-personal"><?= __('Personal Information'); ?></a></li>
    <li class="ui-state-default ui-corner-top ui-state-active"><a href="#profile-tab-about"><?= __('About %username%', array('%username%'=>$sf_user->getCollector()->getUsername())); ?></a></li>
    <!--
    <li style="float: right;">
      <a href="#profile-tab-notifications"><?= __('Notifications'); ?></a>
    </li>
    //-->
	 </ul>

	 <div id="profile-tab-account">
    <?php include_partial('manage/profile_account', array('form' => $form, 'collector' => $collector)); ?>
	 </div>
  <div id="profile-tab-personal">
    <?php include_partial('manage/profile_personal', array('form' => $form['profile'], 'collector' => $collector)); ?>
  </div>
	 <div id="profile-tab-about">
    <?php include_partial('manage/profile_about', array('form' => $form['profile'], 'collector' => $collector)); ?>
  </div>
  <!--
  <div id="profile-tab-notifications">
    <?php include_partial('manage/profile_notifications', array('form' => $form, 'collector' => $collector)); ?>
  </div>
  //-->
</div>

<br clear="all"/><br/><br/>
<div class="span-12" style="text-align: right;">
  <?php cq_button_submit(__('Save Changes'), null, 'float: right;'); ?>
</div>
<div class="clearfix append-bottom">&nbsp;</div>

<?= $form['id']; ?>
<?= $form['profile']['id']; ?>

<?= $form['_csrf_token']; ?>
</form>

<?php cq_javascript_tag(); ?>
<script type="text/javascript">
  
$(document).ready(function()
{
  $("#profile-tabs").tabs();
  $('#collector_password').pstrength();
});
</script>
<?php cq_end_javascript_tag(); ?>
