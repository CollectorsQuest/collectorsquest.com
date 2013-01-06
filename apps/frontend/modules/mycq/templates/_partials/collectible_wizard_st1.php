<?php
/**
 * @var $form CollectibleWizardStep1Form
 * @var $upload_form CollectibleUploadForm
 */

?>
<div class="row-fluid">
  <div class="span4">

    <div id="files-wz1" style="display: none;"></div>

    <div id="dropzone-wz1" class="dropzone single-file Chivo webfont" style="display: none;">
      <div class="alt-view-slot">
        <i class="icon icon-plus"></i>
        <span class="info-text">Drop file here</span>
      </div>
    </div>

    <div id="main-image-set">
      <?php
      include_component('mycq', 'collectibleMultimedia',
        array('collectible' => $form->getObject(), 'mainOnly' => true));
      ?>
    </div>

  </div>
  <div class="span8">

    <form action="<?= url_for('@ajax_mycq?section=collectible&page=upload'); ?>"
          method="post" id="fileupload-wz1" class="ajax form-horizontal" enctype="multipart/form-data">

      <?= $upload_form['thumbnail']->renderRow(); ?>
      <input type="hidden" name="model" value="<?= $model ?>">
      <input type="hidden" name="set-main" value="1">
      <?= $upload_form->renderHiddenFields(); ?>

    </form>

    <form  action="<?= url_for('ajax_mycq', array('section' => 'collectible', 'page' => 'Wizard')); ?>"
           method="post" class="form-horizontal" id="wz-step1">
      <?= $form; ?>
      <input type="hidden" name="step" value="1" />
      <input type="hidden" name="collectible_id" value="<?= $form->getObject()->getId() ?>" />
    </form>
  </div>
</div>

<!-- The template to display files available for upload -->
<script id="template-upload-wz1" type="text/x-tmpl">
  {% for (var i=0, file; file=o.files[i]; i++) { %}
  <div class="template-upload">
    {% if (file.error) { %}
    <div class="error">{%=localeC.fileupload.errors[file.error] || file.error%}</div>
    {% } else if (o.files.valid && !i) { %}
    <div class="preview">
      <span class="fade"></span>
    </div>
    <div class="row-fluid fade">
      <div class="span9 progress progress-info progress-striped active" style="min-height:20px">
        <div class="bar" style="width:0;"></div>
      </div>
      <div class="cancel span2 pull-right">
        {% if (!i) { %}
        <button class="btn btn-warning btn-mini">
          <span>{%=locale.fileupload.cancel%}</span>
        </button>
        {% } %}
      </div>
    </div>
    {% } %}
  </div>
  {% } %}

</script>

<!-- The template to display files available for download -->
<script id="template-download-wz1" type="text/x-tmpl">
  {% for (var i=0, file; file=o.files[i]; i++) { %}
  <div class="template-download file">
    <i class="icon icon-remove-sign" data-multimedia-id="{%=file.multimediaid%}" ></i>
    {% if (file.error) { %}
    <div class="error">
      {%=localeC.fileupload.errors[file.error] || file.error%}
    </div>
    {% } else { %}
    {% if (file.thumbnail) { %}
    <img class="multimedia" width="300"  src="{%=file.thumbnail%}">

    {% } %}

    {% } %}
  </div>
  {% } %}
</script>

<script type="text/javascript">
  window.localeC = {
    "fileupload": {
      "errors": {
        "maxFileSize": "File is too big",
        "minFileSize": "File is too small",
        "acceptFileTypes": "The file seems to be in the wrong format. " +
            "Please make sure your photo is 'GIF', 'JPEG' or 'PNG' file and try again!",
        "maxNumberOfFiles": "Sorry, you can upload only one image",
        "uploadedBytes": "Uploaded bytes exceed file size",
        "emptyResult": "Empty file upload result"
      },
      "error": "Error",
      "start": "Start",
      "cancel": "Cancel",
      "destroy": "Delete"
    }
  };

  $(document).ready(function()
  {
    'use strict';

    $(".chzn-select").chosen();

    $(document).bind('dragover', function (e)
    {
      var dropZone = $('#dropzone-wz1'),
          timeout = window.dropZoneWZ1Timeout;
      if (!timeout) {
        dropZone.show();
        $('#main-image-set').hide();
      } else {
        clearTimeout(timeout);
      }


      window.dropZoneWZ1Timeout = setTimeout(function () {
        window.dropZoneWZ1Timeout = null;
        dropZone.hide();
        $('#main-image-set').show();
        $('#files-wz1').hide();
      }, 100);
    });

    // Initialize the jQuery File Upload widget:
    $('#fileupload-wz1').fileupload();
    $('#fileupload-wz1').fileupload('option', 'autoUpload', true);
    $('#fileupload-wz1').fileupload('option', 'dropZone', $('#dropzone-wz1'));
    $('#fileupload-wz1').fileupload('option', 'filesContainer', $('#files-wz1'));
    $('#fileupload-wz1').fileupload('option', 'limitConcurrentUploads', 1);
    $('#fileupload-wz1').fileupload('option', 'maxNumberOfFiles', 1);
    $('#fileupload-wz1').fileupload('option', 'uploadTemplateId', 'template-upload-wz1');
    $('#fileupload-wz1').fileupload('option', 'downloadTemplateId', 'template-download-wz1');
    $('#fileupload-wz1').fileupload('option', 'previewMaxWidth', 295);
    $('#fileupload-wz1').fileupload('option', 'previewMaxHeight', 800);

    $('#fileupload-wz1')
        .bind('fileuploadadd', function (e, data)
        {
          $('#files-wz1').html('').show();
          $('#main-image-set .main-image-set-container, #dropzone-wz1').hide();

          setTimeout(function ()
          {
            $('#files-wz1').hide();
            if (data.isValidated)
            {
              $('#main-image-set .main-image-set-container').hide();
              $('#files-wz1 .template-upload .fade').addClass('in');
            }
            else
            {
              $('#main-image-set').show();
              $('#main-image-set .main-image-set-container').show();
              $('#fileupload-wz1').fileupload('option', 'maxNumberOfFiles', 1); //update files count
              $('#main-image-set').load(
                  '<?= url_for('@ajax_mycq?section=component&page=collectibleMultimedia&collectible_id=' .
                    $form->getObject()->getId() .  '&mainOnly=true' ); ?>',
                  function () {
                    $('#main-image-set .main-image-set-container').hideLoading();
                  });
            }
            $('#files-wz1').show();
          }, 150);

        })
        .bind('fileuploadstop', function(e, data)
        {
          $('#main-image-set').show();
          $('#main-image-set .main-image-set-container').show();
          $('#fileupload-wz1').fileupload('option', 'limitConcurrentUploads', 1);
          $('#fileupload-wz1').fileupload('option', 'maxNumberOfFiles', 1);
          $('#main-image-set').show();
          $('#main-image-set .main-image-set-container').show().showLoading();
          $('#main-image-set').load(
              '<?= url_for('@ajax_mycq?section=component&page=collectibleMultimedia&collectible_id=' .
                $form->getObject()->getId() .  '&mainOnly=true' ); ?>',
              function () {
                $('#main-image-set .main-image-set-container').hideLoading();
              });
        })

        .bind('fileuploadcompleted', function (e, data)
        {

          $('#files-wz1').html('').hide();

        });

    // Enable iframe cross-domain access via redirect option:
    $('#fileupload-wz1').fileupload(
        'option', 'redirect',
        window.location.href.replace(
            /\/mycq\/[^\/]*$/, '/iframe_xdcomm.html?%s'
        )
    );

    $('#fileupload-wz1').fileupload('option', {
      maxFileSize: <?= cqStatic::getPHPMaxUploadFileSize() // php file upload limit ?>,
      acceptFileTypes: /(\.|\/)(gif|jpe?g|png|bmp)$/i
    });

  });
</script>
