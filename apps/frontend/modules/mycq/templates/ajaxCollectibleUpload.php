<?php
/**
 * @var $form  CollectionCreateForm
 * @var $model string
 * @var $collection_id integer
 */
?>

<form action="<?= url_for('@ajax_mycq?section=collectible&page=upload'); ?>"
      method="post" id="fileupload-c" class="ajax form-horizontal form-modal" enctype="multipart/form-data"
      xmlns="http://www.w3.org/1999/html">

  <?php
    switch (strtolower($model))
    {
      case 'collectible':
      case 'collectibleforsale':
        echo '<h1>Step 1: Upload Item Photo</h1>';
        echo "
          Choose the photo you'd like to use as your main image for this individual item.<br/>
          You will be able to add alternate views later.<br/><br/>
        ";
        break;
      case 'collection':
        echo '<h1>Step 1: Upload Collection Photo</h1>';
        echo "
          Choose the photo you'd like to use as your cover photo for this entire collection.<br/>
          You will be able to add individual items later.<br/><br/>
        ";
        break;
    }
  ?>

  <div id="fileupload-input-box">

    <div id="dropzone-wrapper" class="dropzone-container">
      <div id="dropzone-c" class="dropzone single-file no-items-to-sort-box Chivo webfont spacer-inner">
        <span class="info-no-items-to-sort" style="text-align: center;">
          <strong>Drag</strong> a photo from your computer<br/>
          and <strong>drop it here</strong> to upload.
        </span>
        <div class="info-drop-here" style="line-height: 60px;">
          Drop file here
        </div>
      </div>
    </div>

    <br/><hr/>
    <div style="background: #fff; margin: auto; margin-top: -29px; width: 50px; text-align: center; font-size: 150%;">
      OR
    </div>
    <br/>

    <?= $form['thumbnail']->renderRow(); ?>
    <input type="hidden" name="model" value="<?= $model ?>">

    <?php if (isset($collection_id)): ?>
    <input type="hidden" name="collection_id" value="<?= $collection_id ?>">
    <?php endif; ?>


  </div>

  <div id="fileupload-box" class="hide">
    <!-- The table listing the files available for upload/download -->
    <table class="table table-striped" style="width: 530px;">
      <tbody class="files"></tbody>
    </table>
  </div>
  <div class="form-actions">
    <button type="submit" class="btn btn-primary spacer-right-15">
      Next Step
    </button>
    <button type="reset" class="btn"
            onClick="$(this).parents('.modal').find('.modal-body').dialog2('close')">
      Cancel
    </button>
  </div>

  <?= $form->renderHiddenFields(); ?>
</form>

<!-- The template to display files available for upload -->
<script id="template-upload-c" type="text/x-tmpl">
  {% for (var i=0, file; file=o.files[i]; i++) { %}
  <tr class="template-upload">
    {% if (file.error) { %}
    <td class="error" colspan="5">
      {%=localeC.fileupload.errors[file.error] || file.error%}
    </td>
    {% } else if (o.files.valid && !i) { %}
      <td class="preview"><span class="fade"></span></td>
      <td class="name"><span>{%=o.formatFileName(file.name)%}</span></td>
    <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
    <td>
      <div class="progress progress-info progress-striped active">
        <div class="bar" style="width:0;"></div>
      </div>
    </td>
    <td class="cancel">
      {% if (!i) { %}
      <button class="btn btn-warning btn-mini">
        <span>{%=locale.fileupload.cancel%}</span>
      </button>
      {% } %}
    </td>
    {% } %}
  </tr>
  {% } %}
</script>

<!-- The template to display files available for download -->
<script id="template-download-c" type="text/x-tmpl">
  {% for (var i=0, file; file=o.files[i]; i++) { %}
  <tr class="template-download">
    {% if (file.error) { %}
    <td class="error" colspan="5">
      {%=localeC.fileupload.errors[file.error] || file.error%}
    </td>
    {% } else { %}
    <td class="preview">
      {% if (file.thumbnail) { %}
      <img src="{%=file.thumbnail%}" height="50"/>
      {% } %}
    </td>
    <td class="name" colspan="3"><span>{%=file.name%}</span></td>
    <td class="success">
      <span class="label label-success">Success</span>
    </td>
    {% } %}
  </tr>
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
          var dropZone = $('#dropzone-c'),
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
          if ($('#dropzone-c').length != 0)
          {
              $('#dropzone').removeClass('in hover');
              window.dropZoneTimeout = null;
          }
          window.dropZoneCTimeout = setTimeout(function () {
              window.dropZoneCTimeout = null;
              dropZone.removeClass('in hover');
          }, 100);
      });

    // Dialog should be closed only with cancel upload button
    $('.modal-body.opened').dialog2('options', {'closeOnOverlayClick': false, 'closeOnEscape': false});
    $('.modal-body.opened').parent('.modal').find('.modal-header a.close').hide();

    // Initialize the jQuery File Upload widget:
    $('#fileupload-c').fileupload({stop:function(){}});
    $('#fileupload-c').fileupload('option', 'autoUpload', true);
    $('#fileupload-c').fileupload('option', 'dropZone', $('body'));
    $('#fileupload-c').fileupload('option', 'limitConcurrentUploads', 1);
    $('#fileupload-c').fileupload('option', 'maxNumberOfFiles', 1);
    $('#fileupload-c').fileupload('option', 'uploadTemplateId', 'template-upload-c');
    $('#fileupload-c').fileupload('option', 'downloadTemplateId', 'template-download-c');

    $('#fileupload-c')
      .bind('fileuploadadd', function (e, data) {
        // Remove already added items and show upload box
        $('.template-upload td.error').each(function(){
          $('#fileupload-c').fileupload('option', 'maxNumberOfFiles', 1); //update files count
          $(this).closest('.template-upload').remove();
        });
       // $('.template-upload').remove();
        $('#fileupload-box').removeClass('hide');
      })
      .bind('fileuploadstart', function(e, data) {
        // Hide drop zone and file input
        $('.progress-header').removeClass('hide');
        $('#fileupload-input-box').addClass('hide');
        $('.modal-footer').hide();
      })
      .bind('fileuploadstop', function(e, data)
      {
        var finish = '<?= url_for('@mycq_upload_finish?batch='. $batch); ?>';

        if ($('#fileupload-modal td.error').length > 0)
        {
          $('#button-fileupload').html('Finish Upload');
          $('#button-fileupload').removeClass('btn-danger');
          $('#button-fileupload').attr('href', finish);
        }
        else
        {
          window.location.href = finish;
        }
      })
      .bind('fileuploadcompleted', function (e, data)
        {
          // check for upload error
          if (data.result[0].error)
          {
            // and display it
            data.context.text(data.result[0].error);
          }
          // if no error
          else
          {
            // destroy file upload and show dialog with next step
            var options = {
              modal: true
            };
            options.content = data.result[0].redirect;
            $('#fileupload-c').stop();
            $('#fileupload-c').fileupload('destroy');
            $('.modal-body.opened').dialog2('close');
            $("<div></div>").dialog2(options);
          }
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

    // Load existing files:
    $('#fileupload-c').each(function () {
      var that = this;
      $.getJSON(this.action, function (result) {
        if (result && result.length) {
          $(that).fileupload('option', 'done')
            .call(that, null, {result: result});
        }
      });
    });

  });
</script>
