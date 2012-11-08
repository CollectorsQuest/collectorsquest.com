<?php
/**
 * @var $form  CollectionCreateForm
 * @var $model string
 * @var $collection_id integer
 */
?>

<form action="<?= url_for('@ajax_mycq?section=collectible&page=upload'); ?>"
      method="post" id="fileupload-c" class="ajax form-horizontal form-modal" enctype="multipart/form-data">

  <?php
    switch (strtolower($model))
    {
      case 'collectible':
      case 'collectibleforsale':
        echo '<h1>Step 1: Upload Item Photo</h1>';
        break;
      case 'collection':
        echo '<h1>Step 1: Upload Collection Photo</h1>';
        break;
    }
  ?>

  <div id="fileupload-input-box">
    <?= $form['thumbnail']->renderRow(); ?>
    <input type="hidden" name="model" value="<?= $model ?>">

    <?php if (isset($collection_id)): ?>
    <input type="hidden" name="collection_id" value="<?= $collection_id ?>">
    <?php endif; ?>

    <div id="dropzone-wrapper" class="dropzone-container">
      <div id="dropzone-c" class="dropzone collectibles-to-sort no-items-to-sort-box Chivo webfont spacer-inner">
        <span class="info-no-items-to-sort" style="text-align: center;">
          <strong>Drag and drop</strong> a single photo from your computer
        </span>
      </div>
    </div>
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
                  timeout = window.dropZoneCTimeoutC;
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
              window.dropCZoneTimeout = null;
              dropZone.removeClass('in hover');
          }, 100);
      });

    // Dialog should be closed only with cancel upload button
    $('.modal-body.opened').dialog2('options', {'closeOnOverlayClick': false, 'closeOnEscape': false});
    $('.modal-body.opened').parent('.modal').find('.modal-header a.close').hide();

    // Initialize the jQuery File Upload widget:
    $('#fileupload-c').fileupload({stop:function(){}});
    $('#fileupload-c').fileupload('option', 'autoUpload', true);
    $('#fileupload-c').fileupload('option', 'dropZone', $('#dropzone-c'));
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
          // Destroy file upload and show dialog with next step
          var options = {
            modal: true
          };
          options.content = data.result[0].redirect;
          $('#fileupload-c').stop();
          $('#fileupload-c').fileupload('destroy');
          $('.modal-body.opened').dialog2('close');
          $("<div></div>").dialog2(options);
        });

    // Enable iframe cross-domain access via redirect option:
    $('#fileupload-c').fileupload(
      'option', 'redirect',
      window.location.href.replace(
        /\/mycq\/[^\/]*$/, '/iframe_xdcomm.html?%s'
      )
    );

    $('#fileupload-c').fileupload('option', {
      maxFileSize: 10000000,
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
