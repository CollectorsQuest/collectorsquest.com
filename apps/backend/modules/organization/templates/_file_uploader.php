<?php use_javascript('swfobject.js'); ?>

<div  style="margin-left: 135px;">
<div id="uploader_wrapper">
  <a href="http://www.adobe.com/go/getflashplayer">
    <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player">
  </a>
</div>
</div>

<script type="text/javascript">
$(document).ready(function()
{
  var flashvars = {};
  flashvars.uploadUrl = "<?php echo url_for('@ajax_multimedia_upload?model=Organization&model_id='. $form->getObject()->getId()); ?>";
//  flashvars.redirectUploadUrl = "<?php echo url_for('organization/edit?id='. $form->getObject()->getId()); ?>";
  flashvars.maxFileSize = "5000000";
  flashvars.fileTypes = "Images%7C%2A.jpg%5C%3B%2A.jpeg%5C%3B%2A.gif%5C%3B%2A.png%5C%3B%2A.pdf";
  flashvars.backgroundColor = "%23FFFFFF";
  flashvars.buttonTextColor = "%23FFFFFF";
  flashvars.buttonBackgroundColor = "%236D84B4";
  flashvars.buttonBorderColor  = "%23898989";
  flashvars.progressUploadCompleteText = "Upload Complete!";
  flashvars.showLink = "No";
  flashvars.labelUploadText = "Select files to upload (you can select more than one file at a time)";
  flashvars.customList = "true";

  var params = {};
  params.loop = "false";
  params.quality = "best";
  params.scale = "showall";
  params.wmode = "window";
  params.allowscriptaccess = "always";

  var attributes = {};
  attributes.id = "uploader";

  swfobject.embedSWF("/swf/backend/upload.swf", "uploader_wrapper", "850", "300", "9.0.0", "/swf/install.swf", flashvars, params, attributes);
});
</script>
