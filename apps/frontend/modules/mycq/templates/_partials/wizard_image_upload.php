<?php
/**
 * @var $form  CollectionCreateForm
 * @var $model string
 * @var $collection_id integer
 */

$imge = $collectible->getPrimaryImage();
?>



<form action="<?= url_for('@ajax_mycq?section=collectible&page=upload'); ?>"
      method="post" id="fileupload-c" class="ajax form-horizontal" enctype="multipart/form-data"
      xmlns="http://www.w3.org/1999/html">



  <div id="fileupload-input-box">

    <div id="wz1-dropzone-wrapper" class="">
      <div id="dropzone-wz1" class="dropzone single-file Chivo webfont<?= $imge ? ' with-file' : '' ?>">
        <span class="info-no-items-to-sort" style="text-align: center;">
          <strong>Drag</strong> a photo from your computer<br/>
          and <strong>drop it here</strong> to upload.
        </span>
        <div class="info-drop-here" style="line-height: 60px;">
          Drop file here
        </div>



      </div>
      <div id="single-fileupload-box">
        <div class="files">
          <?php if ($imge): ?>
          <div class="template-download file">
            <i class="icon icon-remove-sign" data-multimedia-id="<?= $imge->getId() ?>" ></i>
            <?= image_tag_multimedia($imge, '300x0', array('width' => 300)); ?>

          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>


    <?= $form['thumbnail']->renderRow(); ?>
    <input type="hidden" name="model" value="<?= $model ?>">

    <?php if (isset($collection_id)): ?>
    <input type="hidden" name="collection_id" value="<?= $collection_id ?>">

    <?php endif; ?>


  </div>




  <?= $form->renderHiddenFields(); ?>
  <input type="hidden" name="set-main" value="1">
</form>

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
              dropZone.addClass('in');

          } else {
              clearTimeout(timeout);
          }
          if (e.target === dropZone[0]) {
              dropZone.addClass('hover');
          } else {
              dropZone.removeClass('hover');
          }

          window.dropZoneCTimeout = setTimeout(function () {
              window.dropZoneCTimeout = null;
              dropZone.removeClass('in hover');
          }, 100);
      });

    $('.icon-remove-sign').live('click', function() {
      var $img = $(this).parent('div.file');
      var $icon = $(this);

      $icon.hide();
      $img.showLoading();

      $.ajax({
        url: '<?= url_for('@ajax_mycq?section=multimedia&page=delete&encrypt=1'); ?>',
        type: 'post', data: { multimedia_id: $icon.data('multimedia-id') },
        success: function()
        {
          $img.hideLoading();
          $img.closest('.dropzone').removeClass('with-file');
          $img.remove();
        },
        error: function()
        {
          $img.hideLoading();
          $icon.show();
        }
      });
    })

    // Initialize the jQuery File Upload widget:
    $('#fileupload-c').fileupload();
    $('#fileupload-c').fileupload('option', 'autoUpload', true);
    $('#fileupload-c').fileupload('option', 'dropZone', $('#dropzone-wz1'));
    $('#fileupload-c').fileupload('option', 'filesContainer', $('#dropzone-wz1'));
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

        })
//        .bind('fileuploadstart', function(e, data) {
//
//
//        })
//      .bind('fileuploadstop', function(e, data)
//      {
//
//      })
        .bind('fileuploadcompleted', function (e, data)
        {
          console.log(111);
          // check for upload error
          if (data.result[0].error)
          {
            // and display it
            data.context.text(data.result[0].error);
          }
          // if no error
          else
          {

          }
          $('#fileupload-c').fileupload('option', 'maxNumberOfFiles', 1); //update files count
       //   $('#fileupload-c').fileupload('option', 'limitConcurrentUploads', 1);
          $('#fileupload-c').fileupload('option', 'dropZone', $('#wz1-dropzone-wrapper'));

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
