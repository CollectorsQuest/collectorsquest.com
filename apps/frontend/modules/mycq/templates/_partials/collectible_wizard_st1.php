<?php

$imge = $form->getObject()->getPrimaryImage();
?>
<div class="row-fluid">
  <div class="span4">

    <div id="dropzone-wz1" class="dropzone single-file Chivo webfont" style="display: none;">
        <span class="info-no-items-to-sort" style="text-align: center;">
          <strong>Drag</strong> a photo from your computer<br/>
          and <strong>drop it here</strong> to upload.
        </span>
      <div class="info-drop-here" style="line-height: 60px;">
        Drop file here
      </div>
    </div>

      <div id="files-wz1" style="display: none;"></div>

    <div id="main-image-set">
      <?php
      include_component('mycq', 'collectibleMultimedia',
        array('collectible' => $form->getObject(), 'mainOnly' => true));
      ?>
    </div>
  </div>
  <div class="span8">
    <form action="<?= url_for('@ajax_mycq?section=collectible&page=upload'); ?>"
          method="post" id="fileupload-c" class="ajax form-horizontal" enctype="multipart/form-data">

      <?= $upload_form['thumbnail']->renderRow(); ?>
      <input type="hidden" name="model" value="<?= $model ?>">
      <input type="hidden" name="set-main" value="1">
      <?= $upload_form->renderHiddenFields(); ?>

    </form>
    <form  action="<?= url_for('ajax_mycq', array('section' => 'collectible', 'page' => 'Wizard')); ?>"
           method="post" class="form-horizontal" id="wz-step1">
      <?= $form; ?>
    </form>
  </div>
</div>

<input type="hidden" name="step" value="1" />

<!-- The template to display files available for upload -->
<script id="template-upload-c" type="text/x-tmpl">
  {% for (var i=0, file; file=o.files[i]; i++) { %}
  <div class="template-upload file">
    {% if (file.error) { %}
    <div class="error"></div>
    {% } else if (o.files.valid && !i) { %}
    <i class="icon icon-remove-sign"></i>

    <div class="preview">
      <span class="fade"></span>
    </div>
    <div class="upbox">
      <div class="progress progress-info progress-striped active">
        <div class="bar" style="width:10%;"></div>
      </div>
      <div class="cancel">
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
<script id="template-download-c" type="text/x-tmpl">
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

    $(document).bind('dragover', function (e)
    {
      var dropZone = $('#dropzone-wz1'),
          timeout = window.dropZoneCTimeout;
      if (!timeout) {
        dropZone.show();
        $('#main-image-set').hide();
      } else {
        clearTimeout(timeout);
      }


      window.dropZoneCTimeout = setTimeout(function () {
        window.dropZoneCTimeout = null;
        dropZone.hide();
        $('#main-image-set').show();
      }, 100);
    });



    // Initialize the jQuery File Upload widget:
    $('#fileupload-c').fileupload();
    $('#fileupload-c').fileupload('option', 'autoUpload', true);
    $('#fileupload-c').fileupload('option', 'dropZone', $('#dropzone-wz1'));
    $('#fileupload-c').fileupload('option', 'filesContainer', $('#files-wz1'));
    // $('#fileupload-c').fileupload('option', 'limitConcurrentUploads', 1);
    $('#fileupload-c').fileupload('option', 'maxNumberOfFiles', 1);
    $('#fileupload-c').fileupload('option', 'uploadTemplateId', 'template-upload-c');
    $('#fileupload-c').fileupload('option', 'downloadTemplateId', 'template-download-c');
    $('#fileupload-c').fileupload('option', 'previewMaxWidth', 300);
    $('#fileupload-c').fileupload('option', 'previewMaxHeight', 800);

    $('#fileupload-c')
        .bind('fileuploadadd', function (e, data) {
          //  $('.template-download').remove();
//         $('#fileupload-c').fileupload('option', 'maxNumberOfFiles', 1); //update files count
//          $('#fileupload-c').fileupload('option', 'limitConcurrentUploads', 1);
          //     $('#fileupload-c').fileupload('option', 'maxNumberOfFiles', 1); //update files count
        $('#files-wz1').show();
          $('#main-image-set').hide();
          setTimeout(function () {
            $('#main-image-set').hide();
          }, 100);
        })
//        .bind('fileuploadstart', function(e, data) {
//
//
//        })
      .bind('fileuploadstop', function(e, data)
      {

      })
        .bind('fileuploadcompleted', function (e, data)
        {
          var refresh = function()
          {
            $('#files-wz1').html('').hide();
            $('#main-image-set').show();
            $('#main-image-set .main-image-set-container').showLoading();
            $('#main-image-set').load(
                '<?= url_for('@ajax_mycq?section=component&page=collectibleMultimedia&collectible_id=' .
                  $form->getObject()->getId() .  '&mainOnly=true' ); ?>',
                function () {
                  $('#main-image-set .main-image-set-container').hideLoading();
                }
            );
          }
          // check for upload error
          if (data.result[0].error)
          {
            setTimeout(function () {
              refresh();
            }, 2000);
            data.context.text(data.result[0].error);
          }
          // if no error
          else
          {
            refresh();
          }
          $('#fileupload-c').fileupload('option', 'maxNumberOfFiles', 1); //update files count
          //   $('#fileupload-c').fileupload('option', 'limitConcurrentUploads', 1);;

        });

    // Enable iframe cross-domain access via redirect option:
    $('#fileupload-c').fileupload(
        'option', 'redirect',
        window.location.href.replace(
            /\/mycq\/[^\/]*$/, '/iframe_xdcomm.html?%s'
        )
    );

    $('#fileupload-c').fileupload('option', {
      maxFileSize: <?= cqStatic::getPHPMaxUploadFileSize() // php file upload limit ?>,
      acceptFileTypes: /(\.|\/)(gif|jpe?g|png|bmp)$/i
    });



  });
</script>
